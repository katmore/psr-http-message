#!/usr/bin/env php
<?php
/*
 * adds file and class docblocks to a PHP source file
 */
new class() {
   const ME_NAME = 'generate-docblocks.php';
   const ME_USAGE = <<<USAGE
                  [--help]|[<...OPTIONS>] <PATH>
                  --quiet [<...OPTIONS>] <PATH>
                  [<OPTIONS: [--quiet |--verbose]>] <PATH>
USAGE;
   const APP_ROOT = __DIR__ . '/../';
   const VENDOR_AUTOLOAD = self::APP_ROOT . '/vendor/autoload.php';
   
   const DEFAULT_DOCBLOCK_TAG = [
      'author'=>['D. Bird <retran@gmail.com>'],
   ];
   
   const SOURCE_COMMENT_HEADER = <<<HEADER
/*
 * This file is part of the psr7-http package.
 *
 * (c) D. Bird <retran@gmail.com>, All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
HEADER;
   
   public function __construct() {
      global $argv;
      $offset = null;
      /*
       * parse command-line options
       */
      $opt = getopt("huqv", [
         'help',
         'usage',
         'quiet',
         'version',
         'verbose'
      ], $offset);

      /*
       * apply usage/help mode for
       */
      if (count(array_intersect([
         'v',
         'version',
         'help',
         'h',
         'usage',
         'u'
      ], array_keys($opt)))) {
         $this->printUsage();
         return;
      }

      /*
       * include vendor/autoload.php
       */
      if (! is_file(self::VENDOR_AUTOLOAD)) {
         $this->printLine("missing vendor/autoload.php, have you run composer?", static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
         return $this->exitStatus = 1;
      }

      require self::VENDOR_AUTOLOAD;

      /*
       * determine quiet mode
       */
      if (count(array_intersect([
         'q',
         'quiet'
      ], array_keys($opt)))) {
         $this->quietMode = true;
      }

      /*
       * determine verbose mode
       */
      $this->verboseMode = isset($opt['verbose']);

      /*
       * apply command-line argument opt parse offset
       */
      $arg = [];
      if ($offset && count($argv)) {
         $arg = array_slice($argv, $offset);
         array_unshift($arg, $argv[0]);
      }

      /*
       * enforce <PATH> argument exists
       */
      if (empty($phpFile = isset($arg[1]) ? $arg[1] : null)) {
         $this->printLine("missing: <PATH>", static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
         return $this->exitStatus = 2;
      }

      /*
       * replace tilde with $HOME in <PATH>
       */
      ! empty($_SERVER['HOME']) && $phpFile = preg_replace('/^~/', $_SERVER['HOME'], $phpFile);

      if (is_dir($phpFile)) {
         
         if (!$total = $this->generateDirectoryDockblocks($phpFile)) {
            $this->printLine("<PATH> did not contain any .php files that could be processed", static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
         } else {
            $this->printLine("processed $total files");
         }
         
         
      } else {
         if (pathinfo($phpFile,PATHINFO_EXTENSION)!=="php") {
            $this->printLine("invalid <PATH>: must be a .php file or a directory", static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
            return $this->exitStatus = 2;
         }
         try {
            $this->generateFileDockblocks($phpFile);
         } catch ( InvalidArgumentException $e ) {
            if ($e->getCode() === static::INVALID_PHP_FILE_EXCEPTION_CODE) {
               $this->printLine("invalid <PATH>: " . $e->getMessage(), static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
               return $this->exitStatus = 2;
            }
            throw $e;
         }
      }
   }
   
   private function generateDirectoryDockblocks(string $dir,int $total=0) : int {
      $dirFile = array_diff(scandir($dir), ['..', '.']);
      array_walk($dirFile,function(string $basename) use(&$dir,&$total) {
         $path = "$dir/$basename";
         if (is_dir($path)) {
            $total = $this->generateDirectoryDockblocks($path,$total);
         } else
         if (is_file($path) && pathinfo($path,PATHINFO_EXTENSION)==='php') {
            $this->generateFileDockblocks($path);
            $total++;
         }
         
      });
      return $total;
   }

   const INVALID_PHP_FILE_EXCEPTION_CODE = 702999;
   const GENERATE_FAILURE_EXCEPTION_CODE = 702998;
   
   private function writePhpFile(string $doc_comment,bool $existing_doc_block,string $php_file,int $start_line) : void {
      $line = file($php_file);
      if ($existing_doc_block) {
         $classStartLine = $start_line;
         $docblockStartLine = $docblockEndLine = null;
         for($i=$classStartLine-2;$i>0;$i--) {
            //var_dump($line[$i]);
            if (preg_match('/\*\//',$line[$i])) {
               $docblockEndLine = $i+1;
               break 1;
            }
         }
         for($i=$docblockEndLine-2;$i>0;$i--) {
            //var_dump($line[$i]);
            if (preg_match('/\/\*\*/',$line[$i])) {
               $docblockStartLine = $i+1;
               break 1;
            }
         }
      } else {
         //$docblockStartLine = $start_line;
         $fileTop = array_slice($line,0,$start_line-1);
         $fileBot = array_slice($line,$start_line-1);
         $line = $fileTop;
         $line []= '/**';
         $line []= '*/';
         $docblockStartLine = $start_line;
         $docblockEndLine = $start_line+1;
         $line = array_merge($line,$fileBot);
         
      }
      
      $newPhpFile = dirname(dirname($php_file))."/.".basename(dirname($php_file))."/.".basename($php_file);
      if (!is_dir(dirname($newPhpFile))) {
         if (!mkdir(dirname($newPhpFile),0770,true)) {
            throw new RuntimeException("failed to create temp dir: ".dirname($newPhpFile),static::GENERATE_FAILURE_EXCEPTION_CODE);
         }
      }
      $backupFile = dirname(dirname($php_file))."/.".basename(dirname($php_file))."/.".pathinfo($php_file,PATHINFO_FILENAME)."-".md5_file($php_file)."-BACKUP.php";
      if (!file_exists($backupFile)) {
         if (!copy($php_file,$backupFile)) {
            throw new RuntimeException("failed to create backup file: $backupFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
         }
      }
      if (file_exists($newPhpFile)) {
         if (!unlink($newPhpFile)) {
            throw new RuntimeException("failed to remove existing temp file: $newPhpFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
         }
      }
      if (false===file_put_contents($newPhpFile,"")) {
         throw new RuntimeException("failed to initialize temp file: $newPhpFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
      }
      $curline=0;
      array_walk($line,function($v) use(&$curline,$doc_comment,$newPhpFile,$docblockStartLine,$docblockEndLine) {
         $curline++;
         if ($docblockStartLine===$curline) {
            if (false===file_put_contents($newPhpFile,trim($doc_comment)."\n",FILE_APPEND)) {
               throw new RuntimeException("failed to write to temp file: $newPhpFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
            }
         } else if ($curline>$docblockEndLine || $curline<$docblockStartLine) {
            if (false===file_put_contents($newPhpFile,$v,FILE_APPEND)) {
               throw new RuntimeException("failed to write to temp file: $newPhpFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
            }
         }
      });
         
         if (!unlink($php_file)) {
            throw new RuntimeException("failed to remove existing file: $php_file",static::GENERATE_FAILURE_EXCEPTION_CODE);
         }
         
         if (!copy($newPhpFile,$php_file)) {
            throw new RuntimeException("failed to create file: $php_file",static::GENERATE_FAILURE_EXCEPTION_CODE);
         }
   }
   
   private function renderDocComment(string $doc_comment,string $default_summary) : string {
      $rd = phpDocumentor\Reflection\DocBlockFactory::createInstance()->create($doc_comment);
      
      $docblockTag = [];
      $existingTagName = [];
      $tags = $rd->getTags();
      array_walk($tags, function (phpDocumentor\Reflection\DocBlock\Tag $rt) use(&$docblockTag,&$existingTagName) {
         $docblockTag []= $rt->render();
         $existingTagName []= $rt->getName();
      });
         
      $defaultDocblockTag = static::DEFAULT_DOCBLOCK_TAG;
      array_walk($defaultDocblockTag,function(array $defaultTagBody,string $name) use($rd,&$docblockTag,$existingTagName) {
         if (!in_array($name,$existingTagName)) {
            array_walk($defaultTagBody, function (string $body) use($name,&$docblockTag) {
               $docblockTag []= "@$name $body";
            });
         }
      });
         
      $rd = new phpDocumentor\Reflection\DocBlock(
         $rd->getSummary(),
         $rd->getDescription(),
         $rd->getTags());
      $doc_comment = '';
      // $rd->getDescription()->render();
      
      if (empty($summary = $rd->getSummary())) {
         $doc_comment .= " * " . $default_summary . "\n";
      } else {
         $doc_comment .= " * " . $summary . "\n";
      }
      if (!empty($description = $rd->getDescription())) {
         $doc_comment .= " * " . $description . "\n";
      }
      
      //$docblockTag = static::DEFAULT_DOCBLOCK_TAG;
      array_walk($docblockTag,function(string $body) use(&$doc_comment) {
         $doc_comment .= " * $body\n";
      });
         
     $doc_comment = "/**\n " . trim($doc_comment) . "\n */";
     
     return $doc_comment;
   }
   
   private function generateFileDockblocks(string $php_file): void {
      /*
       * check php_file exists
       */
      if (! is_file($php_file)) {
         throw new InvalidArgumentException(
            "path not found ($php_file)",
            static::INVALID_PHP_FILE_EXCEPTION_CODE);
      }

      /*
       * check php_file write perm
       */
      if (! is_writable($php_file)) {
         throw new InvalidArgumentException(
            "missing write permission ($php_file)",
            static::INVALID_PHP_FILE_EXCEPTION_CODE);
      }

      $phpSource = new class(
         $php_file) {
         public $class = [];
         public $interface = [];
         public $trait = [];
         public function enumElements() : array {
            return array_merge([],$this->class,$this->interface,$this->trait);
         }
         public function __construct(string $php_file) {
            $tokens = token_get_all(file_get_contents($php_file));
            // var_dump($tokens);
            $curtoken = null;
            $namespace = "";
            array_walk($tokens,
               function ($token) use (&$curtoken, &$namespace) {
                  if (is_array($token)) {
                     if (in_array($token[0], [
                        T_INTERFACE,
                        T_CLASS,
                        T_NAMESPACE,
                        T_TRAIT
                     ], true)) {
                        $curtoken = $token[0];
                     } else {
                        if ($token[0] === T_STRING) {
                           switch ($curtoken) {
                              case T_INTERFACE :
                                 $this->interface[] = $namespace . "\\" . $token[1];
                                 break;
                              case T_CLASS :
                                 $this->class[] = $namespace . "\\" . $token[1];
                                 break;
                              case T_TRAIT :
                                 $this->trait[] = $namespace . "\\" . $token[1];
                                 break;
                              case T_NAMESPACE :
                                 $namespace = $token[1];
                                 break;
                           }
                           $curtoken = null;
                        }
                     }
                  }
               });
         }
      };

      $elements = $phpSource->enumElements();
      array_walk($elements,
         function ($el) use ($php_file) {
            
            
            $rc = new ReflectionClass($el);
            
            if ($rc->isInterface()) {
               $label = "interface";
            } else if ($rc->isTrait()) {
               $label = "trait";
            } else {
               $label = "class";
            }
            echo "$label: $el\n";
            if (false === ($docComment = $rc->getDocComment())) {
               $docComment = '/** */';
               $existingDocblock = false;
            } else {
               $existingDocblock = true;
            }
            
            $docComment = $this->renderDocComment($docComment,$rc->getShortName() . " $label");
            
            $this->writePhpFile($docComment,$existingDocblock,$php_file,$rc->getStartLine());
            
      });


   }


   /**
    *
    * @var bool quiet mode
    * @private
    */
   private $quietMode = false;

   /**
    *
    * @var bool quiet mode
    * @private
    */
   private $verboseMode = false;

   /**
    *
    * @var int exit status
    * @private
    */
   private $exitStatus = 0;
   const PRINT_LINE_ALWAYS = 1;
   const PRINT_LINE_NOT_QUIET = 2;
   const PRINT_LINE_VERBOSE = 4;
   const PRINT_LINE_ERROR = 8;
   private function printLine(string $line, int $mode = self::PRINT_LINE_NOT_QUIET): bool {
      if (($mode & static::PRINT_LINE_ALWAYS) || (($mode & static::PRINT_LINE_NOT_QUIET) && ! $this->quietMode) || (($mode & static::PRINT_LINE_VERBOSE) && $this->verboseMode)) {
         if ($mode & static::PRINT_LINE_ERROR) {
            $oldER = error_reporting(error_reporting() & ~ \E_WARNING);
            if (false !== ($f = fopen("php://stderr", "w"))) {
               fwrite($f, "$line\n");
            } else {
               echo "$line\n";
            }
            error_reporting($oldER);
            return true;
         }
         echo "$line\n";
         return true;
      }
      return false;
   }
   private function printUsage(): void {
      $line = explode("\n", static::ME_USAGE);
      $this->printLine("usage:", static::PRINT_LINE_ALWAYS);
      array_walk($line, function ($line) {
         $this->printLine("  " . static::ME_NAME . " " . trim($line), static::PRINT_LINE_ALWAYS);
      });
      $this->printLine("", static::PRINT_LINE_ALWAYS);
   }
   public function __destruct() {
      exit($this->exitStatus);
   }
};
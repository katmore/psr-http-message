#!/usr/bin/env php
<?php
/*
 * adds file and class docblocks to a PHP source file
 */
new class() {
   const ME_NAME = 'generate-source-headers.php';
   const ME_USAGE = <<<USAGE
   [--help]|[<...OPTIONS>] <PATH>
   --quiet [<...OPTIONS>] <PATH>
   [<OPTIONS: [--quiet |--verbose]>] <PATH>
USAGE;
   const APP_ROOT = __DIR__ . '/../';
   const VENDOR_AUTOLOAD = self::APP_ROOT . '/vendor/autoload.php';
   
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
         
         if (!$total = $this->generateSourceHeaderDirectory($phpFile)) {
            $this->printLine("<PATH> did not contain any .php files that could be processed", static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
         } else {
            $this->printLine("processed $total files");
         }
         
         
      } else {
         try {
            $this->generateSourceHeaderFile($phpFile);
         } catch ( InvalidArgumentException $e ) {
            if ($e->getCode() === static::INVALID_PHP_FILE_EXCEPTION_CODE) {
               $this->printLine("invalid <PATH>: " . $e->getMessage(), static::PRINT_LINE_ALWAYS | static::PRINT_LINE_ERROR);
               return $this->exitStatus = 2;
            }
            throw $e;
         }
      }
      
   }
   
   
   
   const INVALID_PHP_FILE_EXCEPTION_CODE = 702999;
   
   private function generateSourceHeaderDirectory(string $dir,int $total=0) : int {
      $dirFile = array_diff(scandir($dir), ['..', '.']);
      array_walk($dirFile,function(string $basename) use(&$dir,&$total) {
         $path = "$dir/$basename";
         if (is_dir($path)) {
            $total = $this->generateSourceHeaderDirectory($path,$total);
         } else
            if (is_file($path) && pathinfo($path,PATHINFO_EXTENSION)==='php') {
               $this->generateSourceHeaderFile($path);
               $total++;
            }
         
      });
         return $total;
   }
   
   private function generateSourceHeaderFile(string $php_file) {
      /*
       * check php_file exists
       */
      if (! is_file($php_file)) {
         throw new InvalidArgumentException(
            "not a file ($php_file)",
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
      
      //$topCommentStop = $topCommentStart = null;
      $token = token_get_all(file_get_contents($php_file));
      
      $line = file($php_file,FILE_IGNORE_NEW_LINES);
      
      $namespaceLine = null;
      $commentBeforeNamespace = [];
      array_walk($token,function($tdata) use(&$namespaceLine,&$commentBeforeNamespace) {
         
         if ($namespaceLine===null) {
            
            if ($tdata[0]===T_NAMESPACE) {
               
               $namespaceLine = $tdata[2];
               
            } else if ($tdata[0]===T_DOC_COMMENT||$tdata[0]===T_COMMENT) {
               
               $start = $tdata[2];
               $commentBeforeNamespace []= [
                  'start'=>$start,
                  'stop' => $start+substr_count($tdata[1],"\n"),
               ];
               
            }
         }
      });
      
      if (count($commentBeforeNamespace)) {
         $offset = 0;
         array_walk($commentBeforeNamespace,function(array $data) use(&$token,&$line,&$offset) {
            $fileTop = array_slice($line,0,$data['start']-1-$offset);
            //var_dump($fileTop);
            $fileBot = array_slice($line,$data['stop']-$offset);
            //var_dump(array_slice($fileBot,0,10));
            $offset+=$data['stop']-$data['start']+1;
            $line = $fileTop;
            $line = array_merge($line,$fileBot);
            $token = token_get_all(implode("\n",$line));
         });
      }
      
      $namespaceLine = null;
      $emptyLineBeforeNamespace = [];
      array_walk($token,function($tdata) use(&$namespaceLine,&$emptyLineBeforeNamespace) {
         
         if ($namespaceLine===null) {
            
            if ($tdata[0]===T_NAMESPACE) {
               
               $namespaceLine = $tdata[2];
               
            } else if (ctype_space($tdata[1])) {
               for($i=0;$i<substr_count($tdata[1],"\n");$i++) {
                  $emptyLineBeforeNamespace []= $i+$tdata[2];
               }
            }
         }
      });
         
      if ($namespaceLine!==null) {
         
         //var_dump(array_slice($line,0,10));
         
         if (count($emptyLineBeforeNamespace)) {
            $offset = 0;
            //$i=false;
            array_walk($emptyLineBeforeNamespace,function(int $lineno) use(&$token,&$line,&$offset) {
               //$i=true;
               $fileTop = array_slice($line,0,$lineno-1-$offset);
               //var_dump($fileTop);
               $fileBot = array_slice($line,$lineno-$offset);
               //var_dump(array_slice($fileBot,0,10));
               $offset++;
               $line = $fileTop;
               $line = array_merge($line,$fileBot);
               $token = token_get_all(implode("\n",$line));
            });
         }
      }
      
      $startLine = null;
      array_walk($token,function($tdata) use(&$startLine) {
         if ($startLine===null) {
            if ($tdata[0]===T_NAMESPACE) {
               $startLine=$tdata[2];
            }
         }
      });
         
      if ($startLine==null) {
         array_walk($token,function($tdata) use(&$startLine) {
            if ($startLine===null) {
               if ($tdata[0]===T_OPEN_TAG) {
                  $startLine=$tdata[2];
               }
            }
         });
      }
      
      
      
      $fileTop = array_slice($line,0,$startLine-1);
      $fileBot = array_slice($line,$startLine-1);
      $line = $fileTop;
      
      
      $line []= '';
      $line = array_merge($line,explode("\n",static::SOURCE_COMMENT_HEADER));
      $line []= '';
      $line = array_merge($line,$fileBot);
      
      
      
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
      if (false===file_put_contents($newPhpFile,implode("\n",$line))) {
         throw new RuntimeException("failed to write to temp file: $newPhpFile",static::GENERATE_FAILURE_EXCEPTION_CODE);
      }
      if (!unlink($php_file)) {
         throw new RuntimeException("failed to remove existing file: $php_file",static::GENERATE_FAILURE_EXCEPTION_CODE);
      }
      
      if (!copy($newPhpFile,$php_file)) {
         throw new RuntimeException("failed to create file: $php_file",static::GENERATE_FAILURE_EXCEPTION_CODE);
      }
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
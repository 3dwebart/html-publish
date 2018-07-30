<?php
class FileCache {
	private $filepath = './../WebApp/FileCache/';
        private $filename = 'tmp';
        private $content = '';
        private $ttl; //파일 유지시간(sec)
        private $fp;
	// set log file (path and name)
        
        function __construct($controller,$file,$ttl=60){
            $this->filepath = $this->filepath.$controller;
            $this->filename = $file;
            $this->ttl = $ttl;
        }
        
	// write message to the file
	public function createFileCache($content) {
		@mkdir($this->filepath, 0707);
                @chmod($this->filepath, 0707);
                
		if (!is_resource($this->fp)) {
                    $this->fp = fopen($this->getFullPath(), 'w');
		}
                fwrite($this->fp, $content . PHP_EOL);
	}
        
        // 해당 초 동안 읽을 수 있다.
	public function readFileCache(){
            if (file_exists($this->getFullPath())) {
                if($this->ttl<0){ //ttl 사용안할경우
                    return;
                }
                $write_time = $this->getModifyDate();
                if($write_time){
                    if ($write_time >= (time() - $this->ttl)){
                        return file_get_contents($this->getFullPath());
                    }
                }
            }
            return;
	}
        
        
        public function getModifyDate(){
            if (file_exists($this->getFullPath())) {
                return filemtime($this->getFullPath());
            }
            return;
        }
        
	public function close() {
            fclose($this->fp);
	}
        
        private function getFullPath(){
            return $this->filepath .'/'. $this->filename;
        }
}
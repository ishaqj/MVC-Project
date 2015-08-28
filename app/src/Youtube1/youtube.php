<?php
namespace Ishaq\Youtube;
class youtube {
        
        private $error,$video,$vidarray,$id;
        
        function get($id) {
                // for demo purposes.  Load from array file.
                // you will most likely want to pull information from a database.
                include('videos.php');
                if(
                        !isset($vidarray) ||                            // video array exists
                        !isset($vidarray[$id]) ||                       // video id exists
                        !isset($vidarray[$id]['ytid']) ||       // video youtube id is present.
                        !isset($vidarray[$id]['title'])         // video title is present.
                  )
                  { $this->error = 'Error Embedding Video'; }
                  else { $this->video = $vidarray[$id]; }
        }
        
        function embed_html($width = 610,$height = 390) {
                // error exists?
                if(isset($this->error)) return $this->error;
                
                // width and height is more than 0
                if((INT)$width < 1 || (INT)$height < 1) return 'Incorrect width & height supplied'; 
                
                return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$this->video['ytid'].'?wmode=transparent&rel=0&controls=0&modestbranding=1&showinfo=0" frameborder="0" ></iframe>';
        }
        
        function embed_url() {
                return 'http://www.youtube.com/embed/'.$this->video['ytid'];
        }
                
        function video_title() {
                return $this->video['title'];
        }       
        
        function video_details() {
                return $this->video['details'];
        }       
        
        function video_url() {
                return 'http://www.youtube.com/watch?v='.$this->video['ytid'];
        }       
        
        function video_thumbnail() {
                return $this->video['thumbnail'];
        }       
}

?>
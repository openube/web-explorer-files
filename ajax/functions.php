<?php
	header('Content-type: application/json');

	define("PATH_ROOT", '../');

	if(isset($_POST['action'])){
		
		if($_POST['action'] == "getContentFile"){ echo getContentFile($_POST['file_name'],$_POST['file_path']);}
		if($_POST['action'] == "getListFiles"){ echo getListFiles($_POST['path']);}
	}

	function getContentFile($file_name, $file_path){
		$return = array();

		$path = PATH_ROOT.$file_path.$file_name;
		
		$ext_tab = split('\.',$file_name);
		$ext = $ext_tab[count($ext_tab)-1];

		$content = fread(fopen($path, "r"), filesize($path));
		$content = htmlentities($content);

		$return['content'] = $content;
		$return['ext'] = $ext;
		return json_encode($return);
	}
	function getListFiles($path){
		$returns = array();

		if(preg_match("/\b..\b/", substr($path, 3)) == 0)
			$returns['error'] = "Path Error : content '..' in path file ".$path;
		if(!is_dir(PATH_ROOT.$path))
			$returns['error'] = 'opening folder failed, folder does\'n exist';
		else{
			try{
				$d = dir(PATH_ROOT.$path);
				$i=0;
				while($entry = $d->read()) {
					if($entry != "." && $entry != ".."){
						if(stripos($entry,".") != false) //file
							$return["type"] = "file";	
						else
							$return["type"] = "folder";
					    
					    $return["name"] = $entry;

					   	$returns[] = $return;
					   	unset($return);
					}
				    $i++;
				}
				$d->close();
			} catch(Exception $e){
				$returns['error'] = $e->getMessages();
			}
		}
		return json_encode($returns);
	}
?>
jQuery(document).ready(function($) {
			// init
			$(".content-file").hide();
			addFolderItemBreadcrumb("files"); // base folder
			updateFileList();

			$(".file-list").on("click", "a", function(){
				if($(this).attr("href") == "#folder"){
					addFolderItemBreadcrumb($(this).text());
					$.ajax({
					 	type: "POST",
					  	url: "ajax/functions.php",
					  	data: {
					  		action: "getListFiles",
					  		path: constructPath()
					  	},
					  	dataType: "json"
					}).done(function(data){
					  	constructFileList(data);
					}).fail(function(data){
						console.log("error");
						console.log(data);
					});
				}
				if($(this).attr("href") == "#file"){
					$.ajax({
					 	type: "POST",
					  	url: "ajax/functions.php",
					  	data: {
					  		action: "getContentFile",
					  		file_name: $(this).text(),
					  		file_path: constructPath()
					  	},
					  	dataType: "json"
					}).done(function(data){
					  	constructContentFile(data);
					}).fail(function(data){
						console.log("error");
						console.log(data);
					});
				}

			});
	
			$(".breadcrumb").on("click", "a", function(){
				$(this).parent().nextAll().remove();
				$.ajax({
					type: "POST",
					url: "ajax/functions.php",
					data: {
						action: "getListFiles",
					  	path: constructPath()
					},
					dataType: "json"
				}).done(function(data){
					constructFileList(data);
				}).fail(function(data){
					console.log("error");
					console.log(data);
				});
			});

			$(".file-list li a").click(function(){
				var type = $(this).attr("href");
				type = type.substring(1,type.length);
				var text = $(this).text();

				if(type == "folder"){
					addFolderItemBreadcrumb(text);
				}
				if(type == "file"){
					alert("open the file : "+constructPath());
				}
			});
		});

		function updateFileList(){
			$.ajax({
			 	type: "POST",
			  	url: "ajax/functions.php",
			  	data: {
			  		action: "getListFiles",
			  		path: constructPath()
			  	},
			  	dataType: "json"
			}).done(function(data){
			  	constructFileList(data);
			});
		}
		function addFolderItemBreadcrumb(folder){
			var item = '<li><a href="#">'+folder+'</a> <span class="divider">/</span></li>';
			$(".breadcrumb").append(item);
		}
		function addItemBreadcrumb(file){
			var item = '<li class="active">'+file+'</li>';
			$(".breadcrumb").append(item);
		}
		function constructPath(){
			var path = "";
			$(".breadcrumb li a").each(function(){
				path += $(this).text()+'/';
			});
			return path;
		}
		function constructFileList(json){
			// Errors Gestion
			if(json.error)
				showErrors(json.error);
			else{
				$(".content-file").slideUp('fast',function(){
					$('.file-list').html("");
					var folders = new Array();

					// ** FOLDERS
					for(var i=0;i<json.length;i++){
						if(json[i].type === "folder")
							folders.push(json[i].name);
					}
					for(var i=0;i<folders.length;i++){
						folders.sort();
						var elem = '<li><a href="#folder" class="folder">'+folders[i]+'</a></li>';

						$('.file-list').append(elem);
					}
					// ** FILES
					var files = new Array();
					for(var i=0;i<json.length;i++){
						if(json[i].type === "file")
							files.push(json[i].name);
					}
					for(var i=0;i<files.length;i++){
						files.sort();
						var elem = '<li><a href="#file">'+files[i]+'</a></li>';

						$('.file-list').append(elem);
					}
				});
			}
		}

		function constructContentFile(json){
			$(".content-file").html("");

			console.log(json);
			
			var html_code = document.createElement('code');
			$(html_code).attr("class","language-"+json.ext).html(json.content);

			console.log(html_code);
			
			$(".content-file").html(html_code).slideDown();
		}

		function showErrors(message){
			$("#error .span12 .alert span").text(message);
			$("#error").slideDown(500).delay(5000).slideUp(500);
		}
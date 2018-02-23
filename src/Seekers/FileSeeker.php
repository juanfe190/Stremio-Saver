<?php
namespace StremioSaver\Seekers;

class FileSeeker
{
	const subtitlesDir = '/home/felix/.config/stremio/subtitles';
	const moviesDir = '/home/felix/.config/stremio/stremio-cache';
	const savingDir = '/home/felix/Desktop/Peliculas';

	/**
	 * Busca todo los subtitulos disponibles en stremio
	 * @return Array Nombre de archivos
	 */
	public function getAllAvailableSubtitles()
	{
		return glob(self::subtitlesDir . '/*.srt');
	}


	/**
	 * Copia todos los subtitulos a la pelicula
	 * @param String $movieName Nombre de la pelicula(carpeta) en donde se almacenaran
	 * los subtitlos
	 * @return This 
	 */
	public function saveAllSubtitles($movieName)
	{
		$destination = self::savingDir  . "/$movieName";
		@mkdir($destination);

		foreach ($this->getAllAvailableSubtitles() as $file) 
		{
			$explode = explode('/', $file);
			$fileName = end($explode); 
			copy($file, "$destination/$fileName");
		}

		return $this;
	}


	/**
	 * Retorna posibles candidatos a pelicula
	 * @return Array 
	 */
	public function getAvailableMovieFiles()
	{
		return glob(self::moviesDir . '/*/*');
	}



	/**
	 * Copia la posible pelicula (archivo con mayor tamano)
	 * @param  String $movieName Nombre de la pelicula(carpeta) en donde se almacenaran
	 * @return this            
	 */
	public function saveMovie($movieName)
	{
		$files = $this->getAvailableMovieFiles();
		$biggestFileSize = 0;
		$movieFileCandidate = null;

		foreach ($files as $file) 
		{
			$size = filesize($file);
			if($size > $biggestFileSize)
			{
				$biggestFileSize = $size;
				$movieFileCandidate = $file;
			}
		}
		
		copy($movieFileCandidate, self::savingDir  . "/$movieName/movie.mp4");
		return $this;
	}


	/**
	 * Elimina cache de stremio
	 * @return this
	 */
	public function cleanCachedFiles()
	{
		$dir = self::moviesDir;
		$it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new \RecursiveIteratorIterator($it,
		             \RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
		    if ($file->isDir()){
		        rmdir($file->getRealPath());
		    } else {
		        unlink($file->getRealPath());
		    }
		}


		array_map('unlink', glob(self::subtitlesDir . '/*'));
	}

}
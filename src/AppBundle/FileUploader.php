<?php
namespace AppBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileUploader extends Controller
{
	private $targetDir;
	public function __construct($targetDir)
	{
		$this->targetDir = $targetDir;
	}

	public function upload(UploadedFile $file, $folder, $alias)
	{
		$ext = strtolower($file->getClientOriginalExtension());
		$fileName = $alias .'.'. $ext;

		$types = array('jpg','jpeg','gif','png','docx','pdf','bmp');
		if (in_array($ext, $types)) {
			$file->move($this->targetDir .'/'. $folder, $fileName);
			return $fileName;
		}
		else {
			$this->addFlash(
					'danger',
					"File '". $fileName ."' not uploaded. Please upload images or documents only."
					);

			return null;
		}
	}

	public function setTargetDir($targetDir) {
		$this->targetDir = $targetDir;
	}
	
	public function getTargetDir() {
		
		return $this->targetDir;
	}
}
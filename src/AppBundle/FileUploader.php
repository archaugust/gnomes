<?php
namespace AppBundle;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class FileUploader extends Controller
{
	private $targetDir;
	public function __construct($targetDir)
	{
		$this->targetDir = $targetDir;
	}

	public function upload(UploadedFile $file, $alias)
	{
		$ext = strtolower($file->getClientOriginalExtension());
		$fileName = $alias .'.'. $ext;

		$types = array('jpg','jpeg','gif','png','docx','pdf','bmp');
		if (in_array($ext, $types)) {
			$file->move($this->targetDir, $fileName);
			return $fileName;
		}
		else {
			$session = new Session();

			$session->getFlashBag()->add(
					'danger',
					"File '". $fileName ."' not uploaded. Please upload attachments in .pdf or .docx format, or images only."
					);

			return null;
		}
	}

	public function setTargetDir($targetDir) {
		$this->targetDir = $targetDir;
	}
}
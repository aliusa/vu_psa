<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RouterInterface;

class StorageService
{
    private Filesystem $filesystem;
    private ContainerInterface $container;
    private RouterInterface $router;

    public function __construct(Filesystem $filesystem, ContainerInterface $container, RouterInterface $router)
    {
        $this->filesystem = $filesystem;
        $this->container = $container;
        $this->router = $router;
    }

    public function initStoragePath(string $relativePath = '/var/uploads/storage'):string
    {
        $absPath = $this->container->getParameter('kernel.project_dir') . $relativePath;
        if(!$this->filesystem->exists($absPath)){
            $this->filesystem->mkdir($absPath);
        }

        return $absPath;
    }


    public function getStoragePath(string $path, bool $absolute = false):string
    {
        $abs = $this->initStoragePath($path);
        if($absolute){
            return $abs;
        }else{
            return $path;
        }
    }
    public function getAssetPath(string $relativePath, ?string $assetFile, bool $absolute = false):?string
    {
        if(!$assetFile) return null;
        if($absolute){
            return $this->container->getParameter('kernel.project_dir') . $relativePath . '/' . $assetFile;
        }else{
            return $relativePath . $assetFile;
        }
    }

    //users helper

    public function getEmails(string $relativePath, ?string $assetFile):?array
    {
        $assetFile = $this->getAssetPath($relativePath, $assetFile, true);
        if(!$assetFile || !file_exists($assetFile)) return null;

        $ext = pathinfo($assetFile, PATHINFO_EXTENSION);

        if(!in_array($ext, ['csv', 'xlsx'])){
            return null;
        }

        $emails = [];
        switch ($ext){
            case 'csv':
                if (($handle = fopen($assetFile, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $email = trim($data[0] ?? '');
                        if(!empty($email)){
                            $emails[] = $email;
                        }
                    }
                    fclose($handle);
                }
                break;
            case 'xlsx':
                $reader = new Xlsx();
                $spreadsheet = $reader->load($assetFile);
                $worksheet = $spreadsheet->getActiveSheet();

                $rowIterator = $worksheet->getRowIterator();

                foreach ($rowIterator as $row){
                    foreach ($row->getCellIterator() as $cell){
                        $email = trim(strval($cell->getValue()));
                        if(!empty($email)){
                            $emails[] = $email;
                        }
                        break;
                    }
                }
                break;
        }

        return $emails;
    }
    //endregion

}

<?php


namespace Publiux\laravelcdn\Contracts;


interface FileUploadHandlerInterface
{
    public function getUploadPathForFile($file);
}
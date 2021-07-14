<?php

function getMediaTypeFlashString($media): string
{
    return $media->getType() == 1 ? " image " : " vidéo ";
}
<?php

function manageKeywords($post){
    $keywords = explode(',', $post->getKeywords()[0]);
    for ($i=0; $i<count($keywords); $i++) {
        $keywords[$i] = trim($keywords[$i]);
    }
    $post->setKeywords($keywords);
}
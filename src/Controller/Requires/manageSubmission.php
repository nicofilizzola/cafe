<?php


function manageSubmission($entity, $em){
    $entity->updateTimestamp();
    $em->persist($entity);
    $em->flush();
}
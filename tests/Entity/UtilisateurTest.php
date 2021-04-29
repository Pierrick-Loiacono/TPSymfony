<?php

use PHPUnit\Framework\TestCase;


class UtilisateurTest extends TestCase
{

    public function testNom() {
        $utilisateur = new \App\Entity\Utilisateur();
        $utilisateur->setNom('test');

        $this->assertEquals('test', $utilisateur->getNom());

    }

}
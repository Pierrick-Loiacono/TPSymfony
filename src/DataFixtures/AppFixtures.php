<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fruits = new Categorie();
        $fruits->setLibelle("Fruits")
            ->setVisuel("images/fruits.jpg")
            ->setTexte("De la passion ou de ton imagination");
        $manager->persist($fruits);

        $junkFood = new Categorie();
        $junkFood->setLibelle("Junk Food")
            ->setVisuel("images/junk_food.jpg")
            ->setTexte("Chère et cancérogène, tu es prévenu(e)");
        $manager->persist($junkFood);

        $legumes = new Categorie();
        $legumes->setLibelle("Légumes")
            ->setVisuel("images/legumes.jpg")
            ->setTexte("Plus tu en manges, moins tu en es un");
        $manager->persist($legumes);

        $produit = new Produit();
        $produit->setLibelle("Pomme")
            ->setTexte("Elle est bonne pour la tienne")
            ->setVisuel("images/pommes.jpg")
            ->setPrix(3.42)
            ->setCategorie($fruits);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Poire")
            ->setTexte("Ici tu n'en es pas une")
            ->setVisuel("images/poires.jpg")
            ->setPrix(2.11)
            ->setCategorie($fruits);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Pêche")
            ->setTexte("Elle va te la donner")
            ->setVisuel("images/peche.jpg")
            ->setPrix(2.84)
            ->setCategorie($fruits);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Carotte")
            ->setTexte("C'est bon pour ta vue")
            ->setVisuel("images/carottes.jpg")
            ->setPrix(2.90)
            ->setCategorie($legumes);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Tomate")
            ->setTexte("Fruit ou Légume ? Légume")
            ->setVisuel("images/tomates.jpg")
            ->setPrix(1.70)
            ->setCategorie($legumes);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Chou Romanesco")
            ->setTexte("Mange des fractales")
            ->setVisuel("images/romanesco.jpg")
            ->setPrix(1.81)
            ->setCategorie($legumes);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Nutella")
            ->setTexte("C'est bon, sauf pour ta santé")
            ->setVisuel("images/nutella.jpg")
            ->setPrix(4.50)
            ->setCategorie($junkFood);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Pizza")
            ->setTexte("Y'a pas pire que za")
            ->setVisuel("images/pizza.jpg")
            ->setPrix(8.25)
            ->setCategorie($junkFood);
        $manager->persist($produit);

        $produit = new Produit();
        $produit->setLibelle("Oreo")
            ->setTexte("Seulement si tu es un smartphone")
            ->setVisuel("images/oreo.jpg")
            ->setPrix(2.50)
            ->setCategorie($junkFood);
        $manager->persist($produit);

        $user = new Utilisateur();
        $user->setNom('admin')
            ->setEmail('admin@test.com')
            ->setPrenom('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('$2y$13$dL3a8MmPHnuXUgQOj6o20.kZpDG04vUiGKNv6pKkqnYy522GSiOuK');
        $manager->persist($user);

        $user = new Utilisateur();
        $user->setNom('test')
            ->setPrenom('test')
            ->setEmail('test@test.com')
            ->setPassword('$2y$13$dL3a8MmPHnuXUgQOj6o20.kZpDG04vUiGKNv6pKkqnYy522GSiOuK');
        $manager->persist($user);

        $user = new Utilisateur();
        $user->setNom('Testage')
            ->setPrenom('Testage')
            ->setEmail('Testage@Testage.com')
            ->setPassword('$2y$13$dL3a8MmPHnuXUgQOj6o20.kZpDG04vUiGKNv6pKkqnYy522GSiOuK');
        $manager->persist($user);

        $manager->flush();
    }
}

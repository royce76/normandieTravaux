<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROJECT_REFERENCE = ['projet1', 'projet2','projet3'];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROJECT_REFERENCE as $key => $value) {
          $project = new Project();
          $project->setName('Projet n°'.$key);
          $project->setDescription('Construction n°'.$key);
          $project->setCreationDate(new \DateTime('now'));
          $project->setDeadline(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-10-26 1'.$key.':00:00'));
          if ($key%2 == 0) {
            $project->setStatus("Terminé");
          }
          else {
            $project->setStatus("En cours");
          }
          $project->setUser($this->getReference(UserFixtures::USER_REFERENCE));
          $this->addReference($value, $project);
          $manager->persist($project);
          $manager->flush();
        }

    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}

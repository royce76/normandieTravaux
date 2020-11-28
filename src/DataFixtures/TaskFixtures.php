<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\ProjectFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (ProjectFixtures::PROJECT_REFERENCE as $key => $value) {
          for ($i=0; $i < 4; $i++) {
            $task = new Task();
            $task->setName('Tâche n°'.$i);
            $task->setDescription('Opération n°'.$i);
            $task->setCreationDate(new \DateTime('now'));
            $task->setDeadline(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-10-25 1'.$i.':00:00'));
            if ($i%2 == 0) {
              $task->setStatus("Terminé");
            }
            else {
              $task->setStatus('En cours');
            }
            $task->setProject($this->getReference($value));
            $task->setUser($this->getReference(UserFixtures::USER_REFERENCE));
            $manager->persist($task);
          }
          $manager->flush();
        }

    }

    public function getDependencies()
    {
        return array(
            ProjectFixtures::class,
            UserFixtures::class,
        );
    }
}

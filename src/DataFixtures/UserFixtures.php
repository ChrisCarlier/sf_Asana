<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $roleAdm = new Role();
        $roleAdm->setName('Administrateur');
        $roleAdm->setRoleKey('ROLE_ADMIN');
        $manager->persist($roleAdm);

        $role = new Role();
        $role->setName('Utilisateur');
        $role->setRoleKey('ROLE_USER');
        $manager->persist($role);

        $user = new User();
        $user->setUsername('chris');
        $user->setPassword($this->encoder->encodePassword($user,'biv'));
        $user->setEmail('cj.carlier@gmail.com');
        $user->setRole($roleAdm);
        $manager->persist($user);
        $manager->flush();
    }
}

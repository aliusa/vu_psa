<?php


namespace App\DataFixtures;

use App\Entity\Administrators;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class AdministratorsFixture extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordHasher $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new Administrators();
        $admin->email = 'info@psa.lt';
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, '%LIcob9@93z1U'));
        $manager->persist($admin);

        $manager->flush();
    }
}

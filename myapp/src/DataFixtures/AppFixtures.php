<?php /** @noinspection PhpPrivateFieldCanBeLocalVariableInspection */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Uid\Uuid;


class AppFixtures extends Fixture
{
    /** @var Generator */
    protected $faker;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $article = new Article();
            try {
                $article->setDescription($this->faker->text(random_int(151, 600)));
                $article->setName($this->faker->realText(random_int(12, 55)));
                $manager->persist($article);
            } catch (Exception $e) {
                echo "Seed fail: " . $e->getMessage();
            }
        }
        $manager->flush();
        $roles = ['ROLE_INTERNAL_APPRAISER','ROLE_MANAGER','ROLE_ADMIN','ROLE_USER'];
        $user = new User();
        $user->setFirstName('TEST');
        $user->setLastName("TESTOWY");
        $user->setEmail('exa@op.pl');
        $user->setUuid(Uuid::v4());
        $user->setRoles(['ROLE_INTERNAL_APPRAISER','ROLE_MANAGER','ROLE_ADMIN','ROLE_USER']);
        $user->setPassword(password_hash('1234', PASSWORD_BCRYPT));
        $manager->persist($user);
        for ($i = 0; $i < 50; $i++) {
            try {
                $rand1 = random_int(0,3);
                $rand2 = random_int(0,3);
                $user = new User();
                $role = $rand1 === $rand2 ? [$roles[$rand1]] : [$roles[$rand1], $roles[$rand2]];
                $user->setFirstName($this->faker->firstName);
                $user->setLastName($this->faker->lastName);
                $user->setEmail($this->faker->email);
                $user->setUuid(Uuid::v4());
                $user->setRoles($role);
                $user->setPassword(password_hash('1234', PASSWORD_BCRYPT));

                $manager->persist($user);
            } catch (Exception $e) {
                echo "Seed fail: " . $e->getMessage();
            }
        }

        $manager->flush();
    }
}
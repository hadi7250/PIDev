<?php

namespace App\DataFixtures;

use App\Entity\Cours;
use App\Entity\Chapitre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Initialize Faker for realistic fake data
        $faker = Factory::create('fr_FR'); 
        
        // A list of real educational YouTube video IDs
        $youtubeVideos = [
            'kJrCd5RcC0g', // Video 1
            'mAtkPQO1FcA', // Video 2
            '30LWjhZzg50', // Video 3
            'Y6SIn-o_rNo'  // Video 4
        ];

        // Let's create 5 Fake Courses
        for ($i = 1; $i <= 5; $i++) {
            $cours = new Cours();
            $cours->setTitre("Formation : " . $faker->catchPhrase());
            $cours->setDescription($faker->paragraph());
            
            $manager->persist($cours);

            // For each course, let's add 3 to 4 YouTube Chapters
            foreach ($youtubeVideos as $index => $videoId) {
                $chapitre = new Chapitre();
                $chapitre->setTitre("Chapitre " . ($index + 1) . " - " . $faker->words(3, true));
                
                // Put the YouTube URL into your 'contenu' field
                $chapitre->setContenu("https://www.youtube.com/watch?v=" . $videoId);
                
                $chapitre->setCours($cours); // Link the chapter to the course

                $manager->persist($chapitre);
            }
        }

        // Send all of this to the database
        $manager->flush();
    }
}
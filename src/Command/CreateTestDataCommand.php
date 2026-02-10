<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Discussion;
use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-test-data',
    description: 'Creates test data for the forum'
)]
class CreateTestDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create test categories
        $category1 = new Category();
        $category1->setName('General Discussion');
        $category1->setDescription('General discussions about various topics');
        $category1->setColor('#007bff');
        $category1->setCreatedAt(new \DateTime());

        $category2 = new Category();
        $category2->setName('Technical Support');
        $category2->setDescription('Get help with technical issues');
        $category2->setColor('#28a745');
        $category2->setCreatedAt(new \DateTime());

        $this->entityManager->persist($category1);
        $this->entityManager->persist($category2);

        // Create test discussion
        $discussion = new Discussion();
        $discussion->setTitle('Welcome to the Forum!');
        $discussion->setContent('This is a test discussion to verify the forum is working properly. Feel free to reply and test the functionality!');
        $discussion->setAuthorName('Admin');
        $discussion->setAuthorEmail('admin@forum.com');
        $discussion->setCategory($category1);
        $discussion->setCreatedAt(new \DateTime());

        $this->entityManager->persist($discussion);

        // Create test message
        $message = new Message();
        $message->setContent('This is a test message. The forum is working great!');
        $message->setAuthorName('Test User');
        $message->setAuthorEmail('test@example.com');
        $message->setDiscussion($discussion);
        $message->setIsAuthor(false);
        $message->setCreatedAt(new \DateTime());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        $output->writeln('<info>Test data created successfully!</info>');
        $output->writeln('<info>- 2 categories created</info>');
        $output->writeln('<info>- 1 discussion created</info>');
        $output->writeln('<info>- 1 message created</info>');

        return Command::SUCCESS;
    }
}

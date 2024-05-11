<?php

namespace App\Command;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Загрузка фикстур',
)]
class LoadFixturesCommand extends Command
{
    public function __construct(
        private readonly BookRepository $bookRepository
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        foreach ($this->dataProvider() as $data) {
            $book = new Book();
            $book
                ->setTitle($data['title'])
                ->setAuthor($data['author'])
                ->setPublishedAt($data['publishedAt']);
            $this->bookRepository->save($book, true);
        }

        $io->success('Фикстуры загружены');

        return Command::SUCCESS;
    }

    private function dataProvider(): array
    {
        return [
            [
                'title' => 'Book1',
                'author' => 'Author1',
                'publishedAt' => 1999
            ],
            [
                'title' => 'Book2',
                'author' => 'Author1',
                'publishedAt' => 2000
            ],
            [
                'title' => 'Book3',
                'author' => 'Author1',
                'publishedAt' => 2002
            ],
            [
                'title' => 'Book1',
                'author' => 'Author2',
                'publishedAt' => 2000
            ],
            [
                'title' => 'Book2',
                'author' => 'Author2',
                'publishedAt' => 1999
            ],
            [
                'title' => 'Book1',
                'author' => 'Author3',
                'publishedAt' => 1999
            ],
            [
                'title' => 'Book1',
                'author' => 'Author4',
                'publishedAt' => 2000
            ]
        ];
    }
}

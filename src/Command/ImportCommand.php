<?php

namespace App\Command;

use App\Exception\InvalidFileException;
use App\Reader\ReaderInterface;
use App\Service\ImportServiceInterface;
use App\Validator\FileValidator\FileValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Exception;

class ImportCommand extends Command
{
    /**
     * @var ImportServiceInterface
     */
    private $productImportService;

    /**
     * @var FileValidator
     */
    private $fileValidator;

    /**
     * @var ReaderInterface
     */
    private $reader;

    /**
     * @var string
     */
    protected static $defaultName = 'products:import';

    /**
     * ImportCommand constructor.
     *
     * @param ImportServiceInterface $productImportService
     * @param FileValidator $fileValidator
     * @param ReaderInterface $reader
     * @param string|null $name
     */
    public function __construct(
        ImportServiceInterface $productImportService,
        FileValidator $fileValidator,
        ReaderInterface $reader,
        string $name = null
    ) {
        $this->productImportService = $productImportService;
        $this->fileValidator = $fileValidator;
        $this->reader = $reader;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('import file command')
            ->addArgument('file', InputArgument::REQUIRED, 'file address');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getArgument('file');

        try {
            $this->fileValidator->validateFile($file);

            $this->reader->setFile($file);

            $importResults = $this->productImportService->execute($this->reader, $io);

            $io->success(
                "import was successful\n" .
                "created records: " . $importResults->created() . "\n" .
                "updated records: " . $importResults->updated() . "\n" .
                "errors in records: " . $importResults->errors() . "\n"
            );
        } catch (InvalidFileException $exception) {
            $io->error("invalid file");
            return 1;
        } catch (Exception $e) {
            $io->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}

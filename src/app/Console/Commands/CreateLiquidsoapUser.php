<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Console\Command;
use Illuminate\Hashing\HashManager;
use ReflectionException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Webmozart\Assert\Assert;

/**
 * Creates Liquidsoap user based on environment variables
 */
class CreateLiquidsoapUser extends Command
{
    protected $signature = 'liquidsoap:user {--if-not-exists}';

    protected $description = 'Creates liquidsoap user from environment variables';

    /**
     * @throws ReflectionException
     */
    public function handle(): int
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = app('config');

        /** @var HashManager $hashManager */
        $hashManager = app(HashManager::class);

        $liquidsoapUserConfig = $configRepository['services']['liquidsoap'] ?? null;
        Assert::notNull($liquidsoapUserConfig);

        $username = $liquidsoapUserConfig['username'] ?? null;
        Assert::notEmpty($username, 'Username must not be empty');

        $email = $liquidsoapUserConfig['email'] ?? null;
        Assert::notEmpty($email, 'Email must not be empty');

        $password = $liquidsoapUserConfig['password'] ?? null;
        Assert::notEmpty($password, 'Password must not be empty');

        $userAlreadyExists = User::where('email', $email)->exists();
        if ($userAlreadyExists && (bool) $this->option('if-not-exists')) {
            $this->output->writeln('Liquidsoap user already exists');
            return SymfonyCommand::SUCCESS;
        }

        Assert::false($userAlreadyExists, 'Liquidsoap user must not exist');
        UserFactory::new()->create([
            'name'        => $username,
            'email'       => $email,
            'password'    => $hashManager->make($password),
        ]);

        $this->output->writeln(sprintf(
            'User %s (%s) has been successfully created',
            $username,
            $email
        ));

        return SymfonyCommand::SUCCESS;
    }
}

<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Console\Command;
use ReflectionException;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Webmozart\Assert\Assert;

/**
 * Creates personal access token for liquidsoap user and stores it into the shared file
 */
class CreateLiquidsoapPersonalToken extends Command
{
    protected $signature = 'liquidsoap:personal-token';

    protected $description = 'Creates personal token for liquidsoap user and stores it into the shared file';

    /**
     * @throws ReflectionException
     */
    public function handle(): int
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = app('config');

        $liquidsoapUserConfig = $configRepository['services']['liquidsoap'] ?? null;
        Assert::notNull($liquidsoapUserConfig);

        $email = $liquidsoapUserConfig['email'] ?? null;
        Assert::notEmpty($email);

        $tokenFilePath = $liquidsoapUserConfig['tokenFilePath'] ?? null;
        Assert::notEmpty($tokenFilePath);

        $tokenName = $liquidsoapUserConfig['tokenName'] ?? null;
        Assert::notEmpty($tokenName);

        /** @var User|null $user */
        $user = User::where('email', $email)->first();
        Assert::notNull($user, sprintf('User with email %s must exist', $email));

        // create new personal access token
        $personalToken = $user->createToken($tokenName);

        // save plaintext access token to a file
        $saveResult = file_put_contents($tokenFilePath, $personalToken->plainTextToken, LOCK_SH | LOCK_EX);
        Assert::notFalse($saveResult);

        $this->output->writeln(sprintf(
            'Personal token has been successfully created and saved in: %s',
            $tokenFilePath
        ));

        return SymfonyCommand::SUCCESS;
    }
}

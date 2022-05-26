use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (SecurityConfig $security) {
    // ...

    // Use native password hasher, which auto-selects and migrates the best
    // possible hashing algorithm (currently this is "bcrypt")
    $security->passwordHasher(PasswordAuthenticatedUserInterface::class)
        ->algorithm('auto')
    ;
};

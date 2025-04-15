<?php

namespace App\Security;

use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use App\Exception\Common\User\UserWrongConfiguredException;
use App\Services\Utils\Helper\AccessMapHelper;
use App\Services\Utils\Helper\JWTHelper;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public const PATH_USER_LOGGED = "/logged";
    public const PATH_USER_CHANGE_PASSWORD = "/user/change-password";

    # path non public access per cui escludere le path
    public const EXCLUDED_PATHS = [
        self::PATH_USER_LOGGED,
        self::PATH_USER_CHANGE_PASSWORD,
    ];

    public function __construct(
        private readonly AccessMapHelper $accessMapHelper,
        private readonly RequestStack $requestStack,
        private readonly JWTHelper $JWTHelper
    ) {}

    /**
     * Metodo che viene chiamato alla login
     *
     * @param UserInterface $user
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
        dump('x');
        if (!$user instanceof User) {
            return;
        }
    }

    /**
     * Metodo che viene chiamato a ogni richiesta
     *
     * @param UserInterface|User $user
     * @return void
     */
    public function checkPostAuth(UserInterface|User $user): void
    {
        /*
         * Se l'utente si è autenticato, verifico se la password è scaduta.
         * Il controllo va fatto solo post autenticazione perché se viene fatto nel PreAuth precede quello di verifica password corretta.
         */
        //        SCOMMENTARE IN CASO IL FRONTEND NON GESTISCA AUTONOMAMENTE IL CAMBIO PASSWORD ALLA LOGIN
        //        E COMMENTARE LA PARTE SOTTO CHE SERVE A BLOCCARE EVENTUALI RICHIESTE FATTE IN PATH DIVERSE DA QUELLE PUBBLICHE O LIBERE
        //        if ($user->isCredentialsExpired()) {
        //            throw new CredentialsExpiredException();
        //        }

        ########## CONTROLLO UTENTE ##########
        ###### verifico che se non si trova in path libere, le credenziali non devono essere scadute o temporanee ######

        # verifico che sia attivo
        $this->checkUserActive(user: $user);

        // # verifico che abbia almeno un modulo
        // $this->checkUserModules(user: $user);

        // # verifico che non sia bloccato
        // $this->checkUserLocked(user: $user);

        $request = $this->requestStack->getMainRequest();

        $pathInfo = $request->getPathInfo();
        if (in_array($pathInfo, self::EXCLUDED_PATHS)) {
            # se la path è quella per il recupero dell'utente loggato, controllo comunque il token
            if ($pathInfo === self::PATH_USER_LOGGED) {
                $this->checkLastToken(user: $user);
            }
            return;
        }

        # Se contiene PUBLIC_ACCESS, restituisco true a prescindere nel caso non si tratti della login;
        # qualora non si tratti della login, controllo se ha la password temporanea e in caso che questa non sia scaduta
        if ($this->accessMapHelper->isPublicAccess()) {
            return;
        }

        # verifico che il token della richiesta coincida con l'ultimo utilizzato
        $this->checkLastToken(user: $user);

        // # verifico che le credenziali dell'utente non siano scadute
        // $this->checkPassword(user: $user);
    }

    // /**
    //  * Controllo che la password non sia scaduta
    //  *
    //  * @param User $user
    //  * @return void
    //  */
    // private function checkPassword(User $user): void
    // {
    //     # verifico che le credenziali dell'utente non siano scadute
    //     if ($user->isCredentialsExpired()) {
    //         throw new CredentialsExpiredException();
    //     }
    // }

    /**
     * Verifico che il token della richiesta coincida con l'ultimo utilizzato
     *
     * @param User $user
     * @return void
     */
    private function checkLastToken(User $user): void
    {
        # verifico che stia venendo l'ultimo token fornito all'utente settato tramite i Set-Cookie httpOnly
        # refer to config/packages/lexik_jwt_authentication.yaml
        if ($this->JWTHelper->getJwtToken() !== $user->getLastToken()) {
            throw new ExpiredTokenException();
        }
    }

    // /**
    //  * Controllo che l'utenza abbia almeno un modulo associato
    //  *
    //  * @param User $user
    //  * @return void
    //  */
    // private function checkUserModules(User $user): void
    // {
    //     # se non ha moduli associati, do errore indicando che l'utente non è configurato correttamente
    //     if (!$user->hasActiveUserModules()) {
    //         throw new UserWrongConfiguredException(message: ErrorCodeEnum::ERROR_AUTHENTICATION_006);
    //     }
    // }

    // /**
    //  * Controllo se l'utente è bloccato
    //  *
    //  * @param User $user
    //  * @return void
    //  */
    // private function checkUserLocked(User $user): void
    // {
    //     if ($user->isLocked()) {
    //         throw new LockedException(message: ErrorCodeEnum::ERROR_AUTHENTICATION_005);
    //     }
    // }

    /**
     * Controllo se l'utente è attivo
     *
     * @param User $user
     * @return void
     */
    private function checkUserActive(User $user): void
    {
        if (!$user->isActive()) {
            throw new DisabledException();
        }
    }
}

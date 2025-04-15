<?php

namespace App\Services\Utils\Helper;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTHelper
{
    private ?string $jwtToken = null;
    private ?array $jwtPayload;
    private bool $setTokenAndPayload = false;

    public function __construct(
        private readonly string $cookieDomain,
        private readonly string $authTokenName,
        private readonly JWTTokenManagerInterface $JWTManager,
        private readonly RequestStack $requestStack,
    ) {}

    /**
     * @return ?string
     */
    public function getJwtToken(): ?string
    {
        if (!$this->setTokenAndPayload) {
            $this->setJwtTokenAndPayload();
        }
        return $this->jwtToken;
    }

    /**
     * @return array|null
     */
    public function getJwtPayload(): ?array
    {
        if (!$this->setTokenAndPayload) {
            $this->setJwtTokenAndPayload();
        }
        return $this->jwtPayload;
    }

    /**
     * @return bool
     */
    public function hasJWT(): bool
    {
        if (!$this->setTokenAndPayload) {
            $this->setJwtTokenAndPayload();
        }
        return isset($this->jwtPayload);
    }

    /**
     * Recupero un valore specifico nel payload del jwt.
     * NB: Viene restituito null se il valore non viene trovato o il jwt non è presente nella richiesta
     *
     * @param string $field
     * @return mixed|null
     */
    public function getPayloadField(string $field): mixed
    {
        if (!$this->setTokenAndPayload) {
            $this->setJwtTokenAndPayload();
        }
        return $this->jwtPayload && count($this->jwtPayload) && array_key_exists($field, $this->jwtPayload)
            ? $this->jwtPayload[$field]
            : null;
    }

    /**
     * Imposto il valore di token e payload, se esiste il token
     *
     * @return void
     */
    private function setJwtTokenAndPayload(): void
    {
        if ($this->setTokenAndPayload) {
            return;
        }

        /** @var Request $request */
        $request = $this->requestStack->getMainRequest();
        if ($this->cookieDomain === 'DEV_ENV') {
            $this->jwtToken = $request->headers->has('authorization')
                ? str_replace('Bearer ', '', $request->headers->get("authorization"))
                : null;
        } else {
            $this->jwtToken = $request->cookies->get($this->authTokenName);
        }

        if ($this->jwtToken) {
            try {
                $this->jwtPayload = $this->JWTManager->parse($this->jwtToken);
            } catch (\Throwable $e) {
                $this->jwtToken = "InvalidJWT";
            }
        }

        $this->setTokenAndPayload = true;
    }
}

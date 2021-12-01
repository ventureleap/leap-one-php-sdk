<?php


namespace VentureLeap\LeapOnePhpSdk\Services\TokenProvider;


use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\CacheItem;
use VentureLeap\ConfigurationService\Api\TokenApi;
use VentureLeap\ConfigurationService\ApiException;
use VentureLeap\ConfigurationService\Model\Credentials;
use VentureLeap\LeapOnePhpSdk\Services\ApiProvider\ConfigurationApiProvider;
use VentureLeap\LeapOnePhpSdk\Services\ApiProvider\TokenApiProvider;

class TokenProvider implements TokenProviderInterface
{
    const CACHE_LIFE_TIME = 900;

    /**
     * @var AdapterInterface
     */
    private $cache;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var TokenApiProvider
     */
    private $tokenApiProvider;
    private $applicationId;
    private $applicationSecret;

    public function __construct(
        AdapterInterface $cache,
        LoggerInterface $logger,
        TokenApiProvider $tokenApiProvider,
        string $applicationId,
        string $applicationSecret
    ) {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->tokenApiProvider = $tokenApiProvider;
        $this->applicationId = $applicationId;
        $this->applicationSecret = $applicationSecret;
    }

    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    public function getToken(): string
    {
        if (empty($this->getApplicationId())) {
            return 'no_token';
        }

        $cacheItem = $this->cache->getItem('jwt_token');

        if ($cacheItem->isHit() && $this->isCachedTokenValid($cacheItem->get())) {
            return $cacheItem->get();
        }

        return $this->refreshToken(
            $cacheItem,
            $this->applicationId,
            $this->applicationSecret
        );
    }


    private function isCachedTokenValid(?string $token): bool
    {
        if (null === $token) {
            return false;
        }

        $tokenParts = explode('.', $token);
        $decode = json_decode(base64_decode($tokenParts[1]), true);

        $timestamp = time() + (60 * 3); // timestamp with three minutes buffer;

        if ($timestamp > $decode['exp']) {
            return false;
        }

        return true;
    }

    private function refreshToken(CacheItem $cacheItem, string $applicationId, string $applicationSecret): string
    {
        $credentials = new Credentials(
            [
                'app_id' => $applicationId,
                'app_secret' => $applicationSecret,
            ]
        );

        try {
            $response = $this->tokenApiProvider->getTokenApi()->postCredentialsItem($credentials);
        } catch (ApiException $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }

        $newToken = $response->getToken();

        $cacheItem->set($newToken);
        $cacheItem->expiresAfter(self::CACHE_LIFE_TIME);

        $this->cache->save($cacheItem);

        return $newToken;
    }
}

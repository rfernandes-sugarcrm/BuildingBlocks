<?php
use Sugarcrm\Sugarcrm\Security\HttpClient\ExternalResourceClient;
use Sugarcrm\Sugarcrm\Security\HttpClient\RequestException;

class IFrameAuthDashlet_Api extends ConfigModuleApi
{
    public function registerApiRest()
    {
        return [
            'config' => [
                'reqType' => 'GET',
                'path' => ['MD_IFrameAuthDashlet', 'config'],
                'pathVars' => ['module', ''],
                'method' => 'config',
                'shortHelp' => translate('LBL_RETRIEVES_CONFIG_FOR_GIVEN_MODULE', 'MD_IFrameAuthDashlet'),
                'longHelp' => '',
            ],
            'configCreate' => [
                'reqType' => 'POST',
                'path' => ['MD_IFrameAuthDashlet', 'config'],
                'pathVars' => ['module', ''],
                'method' => 'configSave',
                'shortHelp' => translate('LBL_CREATES_THE_CONFIG_ENTRIES', 'MD_IFrameAuthDashlet'),
                'longHelp' => '',
            ],
            'configUpdate' => [
                'reqType' => 'PUT',
                'path' => ['MD_IFrameAuthDashlet', 'config'],
                'pathVars' => ['module', ''],
                'method' => 'configSave',
                'shortHelp' => translate('LBL_UPDATES_THE_CONFIG_ENTRIES', 'MD_IFrameAuthDashlet'),
                'longHelp' => '',
            ],
        ];
    }

    public function config(ServiceBase $api, $args)
    {
        $this->requireArgs($args, ['module']);
        $adminBean = BeanFactory::getBean('Administration');
        $log = LoggerManager::getLogger();

        if (!empty($args['module']) && $args['module'] === 'MD_IFrameAuthDashlet') {
            $iaConfig = $adminBean->getConfigForModule($args['module'], $this->getPlatform($api->platform));
            global $current_user;

            if (empty($current_user) || empty($current_user->id)) {
                $log->warn('Could not identify the user for IFrameAuthDashlet token generation');
                return;
            }
            $secret = isset($iaConfig['secret_key']) ? $iaConfig['secret_key'] : '';
            $base_url = isset($iaConfig['base_url']) ? $iaConfig['base_url'] : '';
            $log->info('Secret key is' . (!empty($secret) ? ' set' : ' NOT set') . ' and base_url is [' . $base_url . ']');

            // Remove secret_key before returning config for security reasons
            unset($iaConfig['secret_key']);

            // Uncomment this block if you'd like to trigger an external system to get a token
            /*
            try {
                // Set timeout to 60 seconds and 10 max redirects
                $erc = new ExternalResourceClient(60, 10);
                $log->info('Right BEFORE external call to getOrCreateToken');
                $url = $base_url . '/rest/getOrCreateToken';
                $response = $erc->post($url, json_encode([['userId' => $current_user->id, 'foo' => 'bar']]), ['Content-Type' => 'application/json']);
                $log->info('After external call to [' . $url . '] with response [' . $response . ']');
                $httpCode = $response->getStatusCode();
                if ($httpCode >= 400) {
                    $log->fatal('Request failed with status: ' . $httpCode);
                    throw new \SugarApiException('Request failed with status: ' . $httpCode, null, null, $httpCode);
                }
                $parsed = !empty($response) ? json_decode($response->getBody()->getContents(), true) : null;
                $token = hash_hmac('sha256', $parsed['token_from_external'], $secret);
                $iaConfig['token'] = $token;
                return $iaConfig;
            } catch (RequestException $e) {
                $log->fatal('Error: ' . $e->getMessage());
                throw new \SugarApiExceptionError($e->getMessage());
            }
            */

            $token = hash_hmac('sha256', $current_user->id, $secret);
            $log->info('Token for user [' . $current_user->id . '] is [' . $token . ']');
            $iaConfig['token'] = $token;
            $log->info('Final IFrameAuthDashletConfig exposed is [' . print_r($iaConfig, true) . ']');
            return $iaConfig;
        }
        return [];
    }
}

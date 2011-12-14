<?php

/**
 * Adds Newrelic RUM tracking code.

 * @see http://newrelic.com/features/real-user-monitoring
 * @see http://newrelic.com/docs/php/real-user-monitoring-in-php
 */
class asNewRelicFilter extends sfFilter
{
    public function execute(sfFilterChain $filterChain)
    {
        if ($this->isFirstCall() && extension_loaded('newrelic')) {
            // Continue chain
            $filterChain->execute();

            // Get original response code
            $response = $this->context->getResponse();
            $body = $response->getContent();

            // Include Newrelic HTML code at top and bottom of page body
            $body = preg_replace('/<body[^>]*>/i', "$0\n" . newrelic_get_browser_timing_header() . "\n", $body, 1);
            $body = str_ireplace('</body>', "\n" . newrelic_get_browser_timing_footer() . "\n</body>", $body);

            // Update response body
            $response->setContent($body);
        } else {
            // Continue chain
            $filterChain->execute();
        }
    }

}

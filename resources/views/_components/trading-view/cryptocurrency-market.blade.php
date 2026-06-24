<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container mx-auto" style="margin-right: auto; margin-left:auto;">
    <div class="tradingview-widget-container__widget" style=" overflow-x: scroll; box-sizing: border-box;"></div>
    <!--<div class="tradingview-widget-copyright"><a href="https://www.tradingview.com/markets/cryptocurrencies/prices-all/" rel="noopener" target="_blank"><span class="blue-text">Cryptocurrency Markets</span></a> by TradingView</div>-->
    <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
        <?=
        json_encode([
            "width"           => "100%",
            "height"          => "35em",
            "defaultColumn"   => "overview",
            "screener_type"   => "crypto_mkt",
            "displayCurrency" => "USD",
            "colorTheme"      => "light",
            "locale"          => "en",
            "isTransparent"   => true
        ]);
        ?>
    </script>
</div>
<!-- TradingView Widget END -->

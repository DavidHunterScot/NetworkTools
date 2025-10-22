<?php
if( isset( $page_title_html ) && trim( $page_title_html ) )
    $page_title_text = strip_tags( $page_title_html );
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title><?php if( isset( $page_title_text ) && trim( $page_title_text ) ) echo trim( $page_title_text ) . ' - '; ?>Network Tools</title>
        
        <link rel="stylesheet" type="text/css" href="/assets/webfonts/poppins/poppins.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/stylesheet.css">
    </head>
    
    <body>
        <header>
            <div class="container">
                <h1 class="w3-large"><b>Network Tools</b></h1>
            </div>
        </header>

        <nav>
            <div class="container">
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( isset( $params[ 'tool' ] ) && $params[ 'tool' ] == '' ) echo ' current'; ?>" href="/">Home</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( isset( $params[ 'tool' ] ) && $params[ 'tool' ] == 'dns' ) echo ' current'; ?>" href="/dns">DNS</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( isset( $params[ 'tool' ] ) && $params[ 'tool' ] == 'rdns' ) echo ' current'; ?>" href="/rdns">rDNS</a>
                <a class="w3-bar-item w3-button w3-bottombar w3-border-none w3-hover-none<?php if( isset( $params[ 'tool' ] ) && $params[ 'tool' ] == 'whois' ) echo ' current'; ?>" href="/whois">WHOIS</a>
            </div>
        </nav>
        
        <main>
            <div class="container">
                <?php if( isset( $page_title_html ) && trim( $page_title_html ) ): ?><h2><?php echo trim( $page_title_html ); ?></h2><?php endif; ?>
                
                <?php if( isset( $_SESSION[ 'error' ] ) && $_SESSION[ 'error' ] ): ?><div class="alert error"><b>Error:</b> <?php echo $_SESSION[ 'error' ]; ?></div><?php unset( $_SESSION[ 'error' ] ); endif; ?>

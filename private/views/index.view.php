<?php include __DIR__ . DIRECTORY_SEPARATOR . 'header.php'; ?>

                <p>Welcome to Network Tools! A free service to help you with your networking needs.</p>
                
                <div class="tools">
                    <a href="/dns" class="tool">
                        <div class="icon"><img src="/assets/images/icons/search.svg"></div>
                        <div class="title">DNS</div>
                        <div class="description">Lookup the DNS records for a given hostname.</div>
                    </a>

                    <a href="/rdns" class="tool">
                        <div class="icon"><img src="/assets/images/icons/info.svg"></div>
                        <div class="title">rDNS</div>
                        <div class="description">Lookup the hostname associated with an IP address.</div>
                    </a>

                    <a href="/whois" class="tool">
                        <div class="icon"><img src="/assets/images/icons/file.svg"></div>
                        <div class="title">WHOIS</div>
                        <div class="description">Lookup the public WHOIS record for a domain name.</div>
                    </a>
                </div>

<?php include __DIR__ . DIRECTORY_SEPARATOR . 'footer.php'; ?>
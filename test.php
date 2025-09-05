<?php
require "header.php" 
?>
<body>
    <main class="page_wrapper row col-lg-12 overflow-hidden">
        <section class="col-lg-2">
            <!-- Toggle Button (Mobile Only) -->
            <button class="sidebar-toggle-btn btn btn-dark d-lg-none m-2" style="z-index: 1500;" id="sidebarToggle">
                ☰ Menu
            </button>

            <!-- Sidebar -->
            <aside class="sidebar d-flex flex-column sidebar-closed top-2 fixed-top" id="sidebar">
                <!-- Logo and App Name -->
                <div class="sidebar-header">
                    <img src="assets/image/png/logo.png" alt="WLFI Logo" class="sidebar-logo">
                    <span class="gradient-text" style="font-size: 11px;">World Liberty Financial</span>
                </div>

                <!-- Main Navigation Links -->
                <ul id="sidebar-menu" class="nav flex-column mb-4">
                    <li class="nav-item">
                        <a class="nav-link sidebar-link active" href="#">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="#">
                            Connect Wallet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="#">
                            Buy
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="#">
                            Withdraw
                        </a>
                    </li>
                </ul>

                <!-- Connected Wallets Section -->
                <div class="mt-auto pt-4 border-top border-secondary">
                    <p class="text-sm fw-semibold text-muted-custom mb-2">Wallets Connected</p>
                    <ul class="nav flex-column">
                        <li class="nav-item d-flex align-items-center mb-2">
                            <div class="rounded-circle bg-success p-1 me-2" style="width: 10px; height: 10px;"></div>
                            <span class="text-sm">MetaMask</span>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <div class="rounded-circle bg-success p-1 me-2" style="width: 10px; height: 10px;"></div>
                            <span class="text-sm">Trust Wallet</span>
                        </li>
                    </ul>
                </div>
            </aside>
        </section>

        <section class="col-lg-10 left-2 overflow-hidden">
            <section class="hero-section">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <h1 class="mt-5">Shape a New Era of Finance</h1>
                            <h1 class="gradient-text">Be DeFiant</h1>

                            <p class="text" style="font-size: 15px;">
                                They only DeFi platform inspiared by Donald J. Trump. Shape the future of decentralized
                                finance with by owning WLFI tokens.
                            </p>
                        </div>

                        <div class="col-lg-6">
                            <div class="trump-img-wrapper">
                                <img src="assets/image/png/trump.png" class="trump-img-bg img-fluid" alt="Donald Trump">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="countdown-section text-center my-5 overflow-hidden">
                <div class="container ">
                    <div class="row g-4">

                        <!-- Ethereum Card -->
                        <div class="col-md-12 col-lg-3">
                            <a href="#" class="card-custom-crypto">
                                <div class="crypto-header">
                                    <img src="https://assets.coingecko.com/coins/images/279/large/ethereum.png"
                                        alt="Ethereum">
                                    <div class="price-info">
                                        <span class="current-price" id="ETH_live_price">$4,454.11</span>
                                        <span class="change-percentage positive">1.28%</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <p class="crypto-name mb-2 text-start">Ethereum</p>
                                    <div class="other-info">
                                        <span>0.00000000 ETH</span>
                                        <span>$0.00</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- More cards can be added here following the same structure -->

                    </div>
                </div>
            </section>

            <!-- Transactions Section -->
            <section class="container p-4">
                <div class="max-w-md w-full">
                    <div class="mb-6 align-content-center">
                        <h1 class="text-4xl font-bold mb-2 text-white zoom-in-effect scratch-out-title">Latest <span
                                class="gradient-text">$WLFI</span> buys
                        </h1>
                        <p class="text-gray-400">Track the latest token buys as they happen.</p>
                    </div>

                    <div class="bg-gray-900 rounded-xl p-4 shadow-lg border border-gray-800">
                        <div class="flex justify-between text-gray-500 text-sm mb-4 border-b border-gray-700 pb-2">
                            <span class="w-1/2 font-medium" style="color: #FAFAF9;">AGE</span>
                            <span class="w-1/2 text-right font-medium" style="color: #FAFAF9;">TX HASH</span>
                        </div>
                        <div id="transactions-container" class="space-y-3 max-h-96 overflow-y-auto">
                            <!-- Initial transactions will be added here by the script -->
                        </div>
                    </div>
                </div>
            </section>

            <section class="container my-5">
                <div class="row g-2">
                    <!-- Cards with Animation -->
                    <div class="col-md-6">
                        <div class="card-custom text-center">
                            <div class="icon-container">
                                <div class="ring ring1"></div>
                                <div class="ring ring2"></div>
                                <div class="ring ring3"></div>
                                <div class="icon-circle">
                                    <!-- Using a placeholder icon, you can replace this with an SVG or other icon -->
                                    <i class="fas fa-globe-americas"></i>
                                </div>
                            </div>
                            <h3>Steering DeFi's Future</h3>
                            <p>Owning WLFI tokens lets you shape the future of decentralized finance.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="container my-5">
                <div class="row g-3 justify-content-center">
                    <!-- PeckShield Card -->
                    <div class="col-lg-3">
                        <div class="card-custom-sponsor">
                            <div class="logo-container">
                                <img src="https://i.ibb.co/6803h0c/peckshield-logo.png" class="logo peckshield"
                                    alt="PeckShield Logo">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="text-light py-4 mt-5">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <hr>
                        <div class="col-6 d-flex align-items-center gap-3">
                            <a href="#" class="circle-icon" target="_blank">
                                <img src="assets/image/png/circle.png" alt="Circle">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        </div>

                        <div class="col-6 text-end">
                            <p class="mb-0" style="color: #FEED8B; font-size: 15px;">Privacy Policy</p>
                        </div>

                        <div class="mt-4">
                            <div class="col">
                                <p class="text">
                                    © 2024 WorldLiberty Financial, Inc. All Rights Reserved.
                                    If you are resident in the UK, you acknowledge that this information is only
                                    intended to
                                    be available to
                                    persons who meet the requirements of qualified investors (i) who have professional
                                    experience in
                                    matters relating to investments and who fall within the definition of “investment
                                    professional” in Article 19(5) of the Financial Services and Markets Act 2000
                                    (Financial
                                    Promotion) Order 2005, as amended (the “Order”); or (ii) who are high net worth
                                    entities, unincorporated associations or partnerships falling within Article 49(2)
                                    of
                                    the Order; or (iii) any other persons to whom this information may lawfully be
                                    communicated under the Order. Persons who do not fall within these categories should
                                    not
                                    act or rely on the information contained herein.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </section>
    </main>

    <!-- Scroll to Top Button -->
    <a href="#" class="scroll-to-top">
        <i class="bi bi-arrow-up"></i>
    </a>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("sidebarToggle").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("sidebar-closed");
        });
    </script>

    <!-- Custom JS -->
    <script>
        const transactionsContainer = document.getElementById('transactions-container');
        const countdownElement = document.getElementById('countdown');
        const maxTransactions = 10;
        let countdownTimer;
        let nextTransactionTimeout;

        function getRandomTimeAgo() {
            const minutes = Math.floor(Math.random() * 5);
            const seconds = Math.floor(Math.random() * 60);
            if (minutes > 0) {
                return `${minutes}m ${seconds}s ago`;
            } else {
                return `${seconds}s ago`;
            }
        }

        function getRandomTxHash() {
            const chars = 'abcdef0123456789';
            let hash = '0x';
            for (let i = 0; i < 6; i++) {
                hash += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            hash += '...';
            for (let i = 0; i < 4; i++) {
                hash += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return hash;
        }

        function createTransactionElement() {
            const txDiv = document.createElement('div');
            txDiv.className = 'row';
            txDiv.innerHTML = `
                <span class="w-1/2">${getRandomTimeAgo()}</span>
                <span class="w-1/2 text-right font-mono text-gray-400">${getRandomTxHash()}</span>
            `;
            return txDiv;
        }

        function addTransaction() {
            const newTx = createTransactionElement();
            transactionsContainer.prepend(newTx);

            // Remove old transactions to keep the list clean
            while (transactionsContainer.children.length > maxTransactions) {
                transactionsContainer.lastChild.remove();
            }
        }

        function startCountdown(duration) {
            let timeLeft = duration;
            countdownElement.textContent = timeLeft;

            clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                timeLeft--;
                countdownElement.textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                }
            }, 1000);
        }

        function scheduleNextTransaction() {
            // Set a random interval between 5 and 15 seconds
            const nextInterval = Math.floor(Math.random() * 11) + 5;
            startCountdown(nextInterval);

            clearTimeout(nextTransactionTimeout);
            nextTransactionTimeout = setTimeout(() => {
                addTransaction();
                scheduleNextTransaction(); // Schedule the next one after this one happens
            }, nextInterval * 1000);
        }

        // Add some initial transactions when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            for (let i = 0; i < 5; i++) {
                addTransaction();
            }
            scheduleNextTransaction();
        });
    </script>
</body>

</html>
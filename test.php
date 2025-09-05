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
                        <a class="nav-link sidebar-link active" href="./">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#ffffff"
                                fill="212429">
                                <path
                                    d="M13.6903 19.4567C13.5 18.9973 13.5 18.4149 13.5 17.25C13.5 16.0851 13.5 15.5027 13.6903 15.0433C13.944 14.4307 14.4307 13.944 15.0433 13.6903C15.5027 13.5 16.0851 13.5 17.25 13.5C18.4149 13.5 18.9973 13.5 19.4567 13.6903C20.0693 13.944 20.556 14.4307 20.8097 15.0433C21 15.5027 21 16.0851 21 17.25C21 18.4149 21 18.9973 20.8097 19.4567C20.556 20.0693 20.0693 20.556 19.4567 20.8097C18.9973 21 18.4149 21 17.25 21C16.0851 21 15.5027 21 15.0433 20.8097C14.4307 20.556 13.944 20.0693 13.6903 19.4567Z"
                                    stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />
                                <path
                                    d="M13.6903 8.95671C13.5 8.49728 13.5 7.91485 13.5 6.75C13.5 5.58515 13.5 5.00272 13.6903 4.54329C13.944 3.93072 14.4307 3.44404 15.0433 3.1903C15.5027 3 16.0851 3 17.25 3C18.4149 3 18.9973 3 19.4567 3.1903C20.0693 3.44404 20.556 3.93072 20.8097 4.54329C21 5.00272 21 5.58515 21 6.75C21 7.91485 21 8.49728 20.8097 8.95671C20.556 9.56928 20.0693 10.056 19.4567 10.3097C18.9973 10.5 18.4149 10.5 17.25 10.5C16.0851 10.5 15.5027 10.5 15.0433 10.3097C14.4307 10.056 13.944 9.56928 13.6903 8.95671Z"
                                    stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />
                                <path
                                    d="M3.1903 19.4567C3 18.9973 3 18.4149 3 17.25C3 16.0851 3 15.5027 3.1903 15.0433C3.44404 14.4307 3.93072 13.944 4.54329 13.6903C5.00272 13.5 5.58515 13.5 6.75 13.5C7.91485 13.5 8.49728 13.5 8.95671 13.6903C9.56928 13.944 10.056 14.4307 10.3097 15.0433C10.5 15.5027 10.5 16.0851 10.5 17.25C10.5 18.4149 10.5 18.9973 10.3097 19.4567C10.056 20.0693 9.56928 20.556 8.95671 20.8097C8.49728 21 7.91485 21 6.75 21C5.58515 21 5.00272 21 4.54329 20.8097C3.93072 20.556 3.44404 20.0693 3.1903 19.4567Z"
                                    stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />
                                <path
                                    d="M3.1903 8.95671C3 8.49728 3 7.91485 3 6.75C3 5.58515 3 5.00272 3.1903 4.54329C3.44404 3.93072 3.93072 3.44404 4.54329 3.1903C5.00272 3 5.58515 3 6.75 3C7.91485 3 8.49728 3 8.95671 3.1903C9.56928 3.44404 10.056 3.93072 10.3097 4.54329C10.5 5.00272 10.5 5.58515 10.5 6.75C10.5 7.91485 10.5 8.49728 10.3097 8.95671C10.056 9.56928 9.56928 10.056 8.95671 10.3097C8.49728 10.5 7.91485 10.5 6.75 10.5C5.58515 10.5 5.00272 10.5 4.54329 10.3097C3.93072 10.056 3.44404 9.56928 3.1903 8.95671Z"
                                    stroke="#141B34" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="round" />
                            </svg>    
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="wallet_connection">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000"
                            fill="none">
                                <path
                                    d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M2.5 12H21.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <path d="M13 7L17 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                                <circle cx="8.25" cy="7" r="1.25" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                                <circle cx="8.25" cy="17" r="1.25" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></circle>
                                <path d="M13 17L17 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                            Connect Wallet
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="buy">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000"
                            fill="none">
                                <path
                                    d="M3.3457 16.1976L16.1747 3.36866M18.6316 11.0556L16.4321 13.2551M14.5549 15.1099L13.5762 16.0886"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path
                                    d="M3.17467 16.1411C1.60844 14.5749 1.60844 12.0355 3.17467 10.4693L10.4693 3.17467C12.0355 1.60844 14.5749 1.60844 16.1411 3.17467L20.8253 7.85891C22.3916 9.42514 22.3916 11.9645 20.8253 13.5307L13.5307 20.8253C11.9645 22.3916 9.42514 22.3916 7.85891 20.8253L3.17467 16.1411Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path d="M4 22H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                            Buy
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sidebar-link" href="withdraw">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="#000000"
                            fill="none">
                                <path
                                    d="M18.9349 13.9453L18.2646 10.2968C17.9751 8.72096 17.8303 7.93303 17.257 7.46651C16.6837 7 15.8602 7 14.2132 7H9.78685C8.1398 7 7.31628 7 6.74298 7.46651C6.16968 7.93303 6.02492 8.72096 5.73538 10.2968L5.06506 13.9453C4.46408 17.2162 4.16359 18.8517 5.08889 19.9259C6.01419 21 7.72355 21 11.1423 21H12.8577C16.2765 21 17.9858 21 18.9111 19.9259C19.8364 18.8517 19.5359 17.2162 18.9349 13.9453Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M12 10.5V17M9.5 15L12 17.5L14.5 15" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M21 11C21.1568 10.9209 21.2931 10.8212 21.4142 10.6955C22 10.0875 22 9.10893 22 7.15176C22 5.1946 22 4.21602 21.4142 3.60801C20.8284 3 19.8856 3 18 3L6 3C4.11438 3 3.17157 3 2.58579 3.60801C2 4.21602 2 5.1946 2 7.15176C2 9.10893 2 10.0875 2.58579 10.6955C2.70688 10.8212 2.84322 10.9209 3 11"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
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

        <nav class="top-nav d-flex justify-content-end align-items-center mb-4 rounded-3 shadow-lg">
            <span class="me-3 d-none d-md-inline">Welcome, <span
                    class="fw-bold"><?php echo $user_display_name; ?></span></span>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li><a class="dropdown-item" href="#">Account Settings</a></li>
                    <li><a class="dropdown-item disabled text-muted-custom" href="#"><?php echo $user_email; ?></a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#">Log Out</a></li>
                </ul>
            </div>
        </nav>

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
                        <?php 
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

            <!-- The popup modal -->
            <div class="modal fade" id="dontShowAgainModal" tabindex="-1" aria-labelledby="dontShowAgainModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dontShowAgainModalLabel">Welcome!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        This is a new feature. You can dismiss this message for 14 days.
                    </div>
                    <div class="modal-footer">
                        <div class="form-check me-auto">
                        <input class="form-check-input" type="checkbox" value="" id="dontShowAgainCheckbox">
                        <label class="form-check-label" for="dontShowAgainCheckbox">
                            Don't show this again for 14 days
                        </label>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="closeModalButton">OK</button>
                    </div>
                    </div>
                </div>
            </div>

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

        // Pass PHP data to JavaScript
        const shouldShowPopup = <?php echo $js_show_popup; ?>;
        const currentUserId = '<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>';

        // New: Function to handle the popup
        function handlePopup() {
            if (shouldShowPopup && currentUserId) {
                const popupModal = new bootstrap.Modal(document.getElementById('dontShowAgainModal'), {
                    backdrop: 'static', // Prevents modal from closing on outside click
                    keyboard: false // Prevents modal from closing with the escape key
                });
                popupModal.show();

                const closeModalBtn = document.getElementById('closeModalButton');
                const dontShowCheckbox = document.getElementById('dontShowAgainCheckbox');

                closeModalBtn.addEventListener('click', () => {
                    if (dontShowCheckbox.checked) {
                        const date = new Date();
                        // Set cookie to expire in 14 days
                        date.setTime(date.getTime() + (14 * 24 * 60 * 60 * 1000));
                        const expires = "expires=" + date.toUTCString();
                        document.cookie = `popup_dismissed_${currentUserId}=${Date.now()}; ${expires}; path=/; Secure; SameSite=Lax`;
                    }
                });
            }
        }

        // Initialize all components on window load
        window.onload = function () {
            handlePopup();
        };
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
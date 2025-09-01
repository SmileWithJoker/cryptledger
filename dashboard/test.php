<?php
session_start();
require_once "includes/header.php";
require_once "../config/config.php";

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Get the user's data from the session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Connect to the database
$pdo = pdo_connect_mysql();

// Fetch the user's assets from the database
$stmt = $pdo->prepare("SELECT * FROM user_assets WHERE user_id = ?");
$stmt->execute([$user_id]);
$assets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total worth (this is a placeholder, you would use a real-time API for this)
$total_worth = 0.00; // Placeholder for real-time calculation
foreach ($assets as $asset) {
    // In a real application, this would be a lookup against an API
    // For this example, we'll assume a value for demonstration
    // $total_worth += $asset['asset_amount'] * get_live_price($asset['asset_symbol']);
}
?>
<body class="bg-dark text-gray-100 antialiased font-sans">
    
    <div id="userMenu" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-sm" id="userMenuBackdrop"></div>
        <div
            class="absolute transform right-4 top-16 w-[calc(100%-2rem)] sm:w-80 md:w-96 bg-dark-medium rounded-lg shadow-lg py-1 ring-1 ring-white/10 mx-4 sm:mx-0">
            <ul class="divide-y divide-dark-lighter">
                <li>
                    <a href="/profile"
                        class="block px-4 py-2.5 text-sm text-gray-300 hover:text-primary hover:bg-dark-lighter transition-colors">
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block px-4 py-2.5 text-sm text-gray-300 hover:text-primary hover:bg-dark-lighter transition-colors">
                        Logout
                    </a>
                </li>
            </ul>
            <form id="logout-form" action="logout" method="POST" class="hidden">
                <input type="hidden" name="_token" value="89861a904592682316179f81436f4de90da8d056ee5f273db728cddf9b9c766b" autocomplete="off">
            </form>
        </div>
    </div>

    <div class="min-h-screen bg-dark">
        <div class="bg-dark-medium border-b border-dark-lighter">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center">
                    <a href="app/connect_wallet"
                        class="hover:text-indigo-400 transition-colors font-medium" style="color: rgb(255, 120, 95)">
                        Connect Wallet
                    </a>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium">Hi, <?php echo htmlspecialchars($username); ?></p>
                        </div>
                        <button id="userMenuToggle" class="p-2 hover:bg-dark-lighter rounded-full transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <h1 class="text-2xl font-bold mb-2">$<?php echo number_format($total_worth, 2); ?></h1>
                    <div class="inline-block px-3 py-1 bg-dark rounded-lg text-xs text-gray-400">
                        Select token to begin transactions
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php if (count($assets) > 0): ?>
                    <?php foreach ($assets as $asset): ?>
                        <a href="app/assets?id=<?php echo htmlspecialchars($asset['id']); ?>"
                            class="block bg-dark-medium rounded-lg p-4 shadow-lg hover:shadow-xl hover:transform hover:scale-105 transition-all duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <img src="https://assets.coingecko.com/coins/images/279/large/ethereum.png" alt="<?php echo htmlspecialchars($asset['asset_name']); ?>" class="w-8 h-8 rounded-full">
                                <div class="text-right">
                                    <span class="text-sm">
                                        $0.00 <!-- Placeholder for live price -->
                                    </span>
                                    <span class="text-xs block text-red-500">
                                        -0.00% <!-- Placeholder for price history -->
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="font-medium text-sm">
                                    <?php echo htmlspecialchars($asset['asset_name']); ?>
                                </p>
                                <div class="flex justify-between text-xs text-gray-400">
                                    <span>
                                        <span>
                                            <?php echo htmlspecialchars(number_format($asset['asset_amount'], 8)); ?>
                                        </span>
                                        <?php echo htmlspecialchars($asset['asset_symbol']); ?>
                                    </span>
                                    <span class="ETH_fiat_worth">$0.00</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center text-gray-400">
                        You have no assets. Connect your wallet to begin.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenu = document.getElementById('userMenu');
            const userMenuToggle = document.getElementById('userMenuToggle');
            const userMenuBackdrop = document.getElementById('userMenuBackdrop');

            const showUserMenu = () => {
                userMenu.classList.remove('hidden');
                userMenu.classList.add('flex', 'justify-end');
            };

            const hideUserMenu = () => {
                userMenu.classList.remove('flex', 'justify-end');
                userMenu.classList.add('hidden');
            };

            userMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (userMenu.classList.contains('hidden')) {
                    showUserMenu();
                } else {
                    hideUserMenu();
                }
            });

            document.addEventListener('click', (e) => {
                if (!userMenu.contains(e.target) && e.target !== userMenuToggle) {
                    hideUserMenu();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !userMenu.classList.contains('hidden')) {
                    hideUserMenu();
                }
            });
        });

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'google_translate_element');
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>

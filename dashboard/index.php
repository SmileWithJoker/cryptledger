<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Dashboard</title>
    <!-- Use Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Use Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js for the portfolio value graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 antialiased p-4 sm:p-8">
    <div class="container mx-auto">
        <!-- Main Dashboard Container -->
        <div class="bg-gray-800 rounded-2xl shadow-2xl p-6 sm:p-10 mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold mb-2">My Crypto Portfolio</h1>
            <p class="text-gray-400 mb-8">A sleek and modern overview of your digital assets.</p>

            <!-- Portfolio Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Value Card -->
                <div class="bg-gray-700 p-6 rounded-2xl shadow-xl">
                    <p class="text-sm font-semibold text-gray-400 mb-1">Total Portfolio Value</p>
                    <div class="flex items-center">
                        <span class="text-3xl font-bold text-green-400">$2,456.78</span>
                        <span class="ml-3 text-green-400 font-semibold flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L10 11.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" transform="rotate(180 12.5 11)"/>
                            </svg>
                            +5.21%
                        </span>
                    </div>
                </div>
                <!-- Holdings and Performance Cards -->
                <div class="bg-gray-700 p-6 rounded-2xl shadow-xl">
                    <p class="text-sm font-semibold text-gray-400 mb-1">Total Holdings</p>
                    <p class="text-3xl font-bold">4</p>
                </div>
                <div class="bg-gray-700 p-6 rounded-2xl shadow-xl">
                    <p class="text-sm font-semibold text-gray-400 mb-1">24h Change</p>
                    <p class="text-3xl font-bold text-green-400">+$121.54</p>
                </div>
            </div>

            <!-- Portfolio Value Chart -->
            <div class="bg-gray-700 rounded-2xl p-6 shadow-xl mb-8">
                <h2 class="text-xl font-semibold mb-4">Portfolio Value History</h2>
                <canvas id="portfolioChart"></canvas>
            </div>

            <!-- Holdings and Transactions Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Top Holdings List -->
                <div class="bg-gray-700 rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-semibold mb-4">Your Holdings</h2>
                    <ul id="holdingsList">
                        <!-- Holding items will be populated by JavaScript -->
                    </ul>
                </div>

                <!-- Recent Transactions List -->
                <div class="bg-gray-700 rounded-2xl p-6 shadow-xl">
                    <h2 class="text-xl font-semibold mb-4">Recent Transactions</h2>
                    <ul id="transactionsList">
                        <!-- Transaction items will be populated by JavaScript -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data for the dashboard. In a real application, this would come from your PHP backend.
        const portfolioData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            values: [1500, 1650, 1800, 1750, 2000, 2200, 2456]
        };

        const holdings = [
            { name: 'Bitcoin', symbol: 'BTC', value: '1,520.45', change: '+3.15%', icon: 'https://placehold.co/40x40/FF9900/ffffff?text=BTC' },
            { name: 'Ethereum', symbol: 'ETH', value: '854.33', change: '+7.82%', icon: 'https://placehold.co/40x40/627EEA/ffffff?text=ETH' },
            { name: 'Cardano', symbol: 'ADA', value: '80.00', change: '+1.10%', icon: 'https://placehold.co/40x40/3DD6D3/ffffff?text=ADA' },
            { name: 'Ripple', symbol: 'XRP', value: '102.00', change: '+4.55%', icon: 'https://placehold.co/40x40/000000/ffffff?text=XRP' }
        ];

        const transactions = [
            { type: 'Buy', amount: '0.05 BTC', date: '2 days ago', value: '$120.00', status: 'Completed' },
            { type: 'Sell', amount: '0.5 ETH', date: '4 days ago', value: '$110.00', status: 'Completed' },
            { type: 'Deposit', amount: '$500 USD', date: '1 week ago', value: '$500.00', status: 'Completed' },
            { type: 'Buy', amount: '100 ADA', date: '2 weeks ago', value: '$80.00', status: 'Completed' }
        ];

        // Function to render the chart
        function renderChart() {
            const ctx = document.getElementById('portfolioChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: portfolioData.labels,
                    datasets: [{
                        label: 'Portfolio Value ($)',
                        data: portfolioData.values,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#10B981',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#10B981',
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)',
                                borderColor: 'rgba(255, 255, 255, 0.2)'
                            },
                            ticks: {
                                color: '#9CA3AF'
                            }
                        },
                        y: {
                            display: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)',
                                borderColor: 'rgba(255, 255, 255, 0.2)'
                            },
                            ticks: {
                                color: '#9CA3AF',
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            titleColor: '#F3F4F6',
                            bodyColor: '#D1D5DB',
                            borderColor: '#4B5563',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 12
                        }
                    }
                }
            });
        }

        // Function to render holdings
        function renderHoldings() {
            const list = document.getElementById('holdingsList');
            list.innerHTML = '';
            holdings.forEach(item => {
                const listItem = document.createElement('li');
                listItem.className = 'flex items-center justify-between py-3 border-b border-gray-600 last:border-b-0';
                listItem.innerHTML = `
                    <div class="flex items-center">
                        <img src="${item.icon}" alt="${item.name} icon" class="w-8 h-8 rounded-full mr-4">
                        <div>
                            <p class="font-medium">${item.name}</p>
                            <p class="text-sm text-gray-400">${item.symbol}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">$${item.value}</p>
                        <p class="text-sm ${item.change.startsWith('+') ? 'text-green-400' : 'text-red-400'}">${item.change}</p>
                    </div>
                `;
                list.appendChild(listItem);
            });
        }

        // Function to render transactions
        function renderTransactions() {
            const list = document.getElementById('transactionsList');
            list.innerHTML = '';
            transactions.forEach(item => {
                const listItem = document.createElement('li');
                listItem.className = 'flex items-center justify-between py-3 border-b border-gray-600 last:border-b-0';
                listItem.innerHTML = `
                    <div class="flex items-center">
                        <span class="text-sm font-semibold p-2 rounded-full ${item.type === 'Buy' ? 'bg-green-500/20 text-green-400' : item.type === 'Sell' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400'} mr-4">${item.type[0]}</span>
                        <div>
                            <p class="font-medium">${item.type} ${item.amount}</p>
                            <p class="text-sm text-gray-400">${item.date}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-200">${item.value}</p>
                        <p class="text-sm text-green-400">${item.status}</p>
                    </div>
                `;
                list.appendChild(listItem);
            });
        }

        // Initialize all components on window load
        window.onload = function() {
            renderChart();
            renderHoldings();
            renderTransactions();
        };

        // Resize chart on window resize
        window.onresize = function() {
            renderChart();
        };
    </script>
</body>
</html>

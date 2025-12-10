/**
 * Personal Finance App - API Client
 * Handles all API calls and data management
 */

const FinanceAPI = {
    // Base URL for API
    baseUrl: '',

    /**
     * Make API request
     */
    async request(endpoint, options = {}) {
        const url = this.baseUrl + endpoint;
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        const config = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },

    /**
     * GET request
     */
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request(url, { method: 'GET' });
    },

    /**
     * POST request
     */
    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    // ==================== DASHBOARD ====================

    async getDashboard(year, month) {
        return this.get('/api/dashboard', { year, month });
    },

    // ==================== TRANSACTIONS ====================

    async getTransactions(filters = {}) {
        return this.get('/api/transactions', filters);
    },

    async createTransaction(data) {
        return this.post('/api/transactions/create', data);
    },

    async updateTransaction(id, data) {
        return this.post(`/api/transactions/update/${id}`, data);
    },

    async deleteTransaction(id) {
        return this.post(`/api/transactions/delete/${id}`);
    },

    // ==================== WALLETS ====================

    async getWallets() {
        return this.get('/api/wallets');
    },

    async createWallet(data) {
        return this.post('/api/wallets/create', data);
    },

    async updateWallet(id, data) {
        return this.post(`/api/wallets/update/${id}`, data);
    },

    async deleteWallet(id) {
        return this.post(`/api/wallets/delete/${id}`);
    },

    // ==================== CATEGORIES ====================

    async getCategories() {
        return this.get('/api/categories');
    },

    async createCategory(data) {
        return this.post('/api/categories/create', data);
    },

    async updateCategory(id, data) {
        return this.post(`/api/categories/update/${id}`, data);
    },

    async deleteCategory(id) {
        return this.post(`/api/categories/delete/${id}`);
    },

    // ==================== FORM DATA ====================

    async getFormData() {
        return this.get('/api/form-data');
    }
};

/**
 * UI Renderer - Renders data to DOM
 */
const FinanceUI = {
    /**
     * Format currency (Indonesian Rupiah)
     */
    formatCurrency(amount) {
        const value = parseFloat(amount) || 0;
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(value);
    },

    /**
     * Format date
     */
    formatDate(dateString, format = 'short') {
        const date = new Date(dateString);
        if (format === 'short') {
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    },

    /**
     * Show loading state
     */
    showLoading(container) {
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }
        if (container) {
            container.innerHTML = `
                <div class="flex items-center justify-center py-12">
                    <div class="spinner spinner-lg"></div>
                </div>
            `;
        }
    },

    /**
     * Show error state
     */
    showError(container, message = 'Failed to load data') {
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }
        if (container) {
            container.innerHTML = `
                <div class="error-state">
                    <div class="error-state-icon">
                        <i class="ph ph-warning-circle"></i>
                    </div>
                    <h4 class="error-state-title">Error</h4>
                    <p class="error-state-text">${message}</p>
                    <button onclick="location.reload()" class="btn btn-primary btn-sm mt-4">
                        <i class="ph ph-arrow-clockwise"></i> Retry
                    </button>
                </div>
            `;
        }
    },

    /**
     * Show empty state
     */
    showEmpty(container, icon = 'ph-file-dashed', title = 'No Data', message = 'No data available', action = null) {
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }
        if (container) {
            let actionHtml = '';
            if (action) {
                actionHtml = `<a href="${action.url}" class="btn btn-primary btn-sm mt-4">${action.label}</a>`;
            }
            container.innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ph ${icon}"></i>
                    </div>
                    <h4 class="empty-state-title">${title}</h4>
                    <p class="empty-state-text">${message}</p>
                    ${actionHtml}
                </div>
            `;
        }
    },

    /**
     * Show toast notification
     */
    showToast(message, type = 'success') {
        // Use SweetAlert2 if available
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            alert(message);
        }
    },

    /**
     * Get sync checksums
     */
    async getSync() {
        return this.get('/api/sync');
    },

    /**
     * Confirm dialog
     */
    async confirm(title, text, confirmText = 'Yes', cancelText = 'Cancel') {
        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            });
            return result.isConfirmed;
        }
        return confirm(`${title}\n${text}`);
    }
};

/**
 * Finance Store - Client Side State Management
 */
const FinanceStore = {
    data: {
        transactions: [],
        wallets: [],
        categories: [],
        checksums: { transactions: {}, wallets: {}, categories: {} },
        lastUpdated: 0,
        initialized: false
    },
    listeners: [],

    async init() {
        if (this.data.initialized) return;
        await this.loadAll();
        this.data.initialized = true;
        this.startPolling();
    },

    async loadAll() {
        try {
            // Fetch all data in parallel
            // Limit 10000 for transactions to get "all"
            const [transactionsRes, walletsRes, categoriesRes, syncRes] = await Promise.all([
                FinanceAPI.getTransactions({ limit: 10000 }),
                FinanceAPI.getWallets(),
                FinanceAPI.getCategories(),
                FinanceAPI.getSync()
            ]);

            if (transactionsRes.success) this.data.transactions = transactionsRes.data.transactions;
            if (walletsRes.success) this.data.wallets = walletsRes.data.wallets;
            if (categoriesRes.success) this.data.categories = categoriesRes.data.categories; // Note: API returns { categories: [], income_categories: [], ... }

            if (syncRes.success) {
                this.data.checksums = syncRes.data;
                this.data.lastUpdated = Date.now();
            }

            this.notify();
        } catch (error) {
            console.error('Failed to load initial data:', error);
        }
    },

    async sync() {
        try {
            const response = await FinanceAPI.getSync();
            if (response.success) {
                const serverChecksums = response.data;
                if (this.needsUpdate(serverChecksums)) {
                    console.log('Data changed, syncing...');
                    await this.loadAll();
                }
            }
        } catch (error) {
            console.error('Sync failed:', error);
        }
    },

    needsUpdate(serverChecksums) {
        const local = this.data.checksums;

        // Check transactions
        if (serverChecksums.transactions.count !== local.transactions.count ||
            serverChecksums.transactions.sum !== local.transactions.sum ||
            serverChecksums.transactions.last_id !== local.transactions.last_id) return true;

        // Check wallets
        if (serverChecksums.wallets.count !== local.wallets.count ||
            serverChecksums.wallets.last_id !== local.wallets.last_id) return true;

        // Check categories
        if (serverChecksums.categories.count !== local.categories.count ||
            serverChecksums.categories.last_id !== local.categories.last_id) return true;

        return false;
    },

    startPolling() {
        setInterval(() => this.sync(), 5000);
    },

    subscribe(listener) {
        this.listeners.push(listener);
        // Call immediately with current data
        if (this.data.initialized) listener(this.data);
    },

    notify() {
        this.listeners.forEach(l => l(this.data));
    },

    // Getters
    getTransactions() { return this.data.transactions; },
    getWallets() { return this.data.wallets; },
    getCategories() { return this.data.categories; }
};

/**
 * Dashboard Page Controller
 */
const DashboardPage = {
    charts: {},
    currentYear: new Date().getFullYear(),
    currentMonth: new Date().getMonth() + 1,

    async init() {
        // Get current filters from URL or use defaults
        const urlParams = new URLSearchParams(window.location.search);
        this.currentYear = parseInt(urlParams.get('year')) || new Date().getFullYear();
        this.currentMonth = parseInt(urlParams.get('month')) || (new Date().getMonth() + 1);

        // Subscribe to store updates
        FinanceStore.subscribe(() => {
            this.loadData();
        });

        // Initial load
        await FinanceStore.init();
        this.loadData();
    },

    loadData() {
        try {
            // Show loading states if not initialized
            if (!FinanceStore.data.initialized) {
                this.showLoadingStates();
                return;
            }

            const transactions = FinanceStore.getTransactions();
            const wallets = FinanceStore.getWallets();
            const categories = FinanceStore.getCategories();

            // Filter transactions for current month/year for summary
            const currentMonthTransactions = transactions.filter(t => {
                const date = new Date(t.date);
                return date.getFullYear() === this.currentYear && (date.getMonth() + 1) === this.currentMonth;
            });

            // Calculate Summary
            const summary = this.calculateSummary(currentMonthTransactions, wallets);
            this.renderSummary(summary);

            // Recent Transactions (Global, not filtered by month)
            this.renderRecentTransactions(transactions.slice(0, 5));

            // Wallet Balances (from Store directly)
            this.renderWalletBalances(wallets);

            // Expense/Income by Category (Current Month)
            const expenseByCategory = this.calculateCategoryBreakdown(currentMonthTransactions, 'expense');
            const incomeByCategory = this.calculateCategoryBreakdown(currentMonthTransactions, 'income');

            this.renderExpenseByCategory(expenseByCategory);
            this.renderIncomeByCategory(incomeByCategory);

            // Charts
            const monthlyTrends = this.calculateMonthlyTrends(transactions);
            this.renderMonthlyTrendChart(monthlyTrends);
            this.renderCategoryBreakdownChart(expenseByCategory);
            this.renderIncomeBreakdownChart(incomeByCategory);

        } catch (error) {
            console.error('Dashboard load error:', error);
            FinanceUI.showToast('Failed to load dashboard data: ' + (error.message || 'Unknown error'), 'error');
        }
    },

    calculateSummary(transactions, wallets) {
        let income = 0;
        let expense = 0;
        transactions.forEach(t => {
            if (t.type === 'income') income += parseFloat(t.amount);
            else expense += parseFloat(t.amount);
        });

        // Total wallet balance is sum of all wallet balances
        // Note: Wallet balance in store might be stale if we don't recalculate it from transactions?
        // Actually, Wallet model calculates balance on the fly.
        // But FinanceStore.wallets comes from API which has balance.
        // If we add a transaction locally, we should update wallet balance locally?
        // For now, let's trust the store's wallet data (which is synced).
        // BUT, if we add a transaction, we haven't implemented "optimistic UI" for adding yet.
        // We are just syncing. So store data is from server.
        // So wallet balance is correct from server.
        const totalWalletBalance = wallets.reduce((sum, w) => sum + parseFloat(w.balance || 0), 0);

        return {
            total_income: income,
            total_expense: expense,
            net_balance: income - expense,
            total_wallet_balance: totalWalletBalance
        };
    },

    calculateCategoryBreakdown(transactions, type) {
        const map = {};
        transactions.filter(t => t.type === type).forEach(t => {
            const name = t.category_name || 'Uncategorized';
            if (!map[name]) map[name] = 0;
            map[name] += parseFloat(t.amount);
        });

        return Object.entries(map)
            .map(([name, amount]) => ({ category_name: name, total_amount: amount }))
            .sort((a, b) => b.total_amount - a.total_amount);
    },

    calculateMonthlyTrends(transactions) {
        // Last 6 months
        const trends = [];
        const today = new Date();
        for (let i = 5; i >= 0; i--) {
            const d = new Date(today.getFullYear(), today.getMonth() - i, 1);
            const year = d.getFullYear();
            const month = d.getMonth() + 1;

            let income = 0;
            let expense = 0;

            transactions.forEach(t => {
                const tDate = new Date(t.date);
                if (tDate.getFullYear() === year && (tDate.getMonth() + 1) === month) {
                    if (t.type === 'income') income += parseFloat(t.amount);
                    else expense += parseFloat(t.amount);
                }
            });

            trends.push({ year, month, total_income: income, total_expense: expense });
        }
        return trends;
    },

    showLoadingStates() {
        const summaryCards = document.querySelectorAll('[data-summary]');
        summaryCards.forEach(card => {
            const valueEl = card.querySelector('[data-value]');
            if (valueEl) {
                valueEl.innerHTML = '<span class="skeleton w-24 h-8"></span>';
            }
        });
    },

    renderSummary(summary) {
        // Update summary cards
        const incomeEl = document.querySelector('[data-summary="income"] [data-value]');
        const expenseEl = document.querySelector('[data-summary="expense"] [data-value]');
        const netEl = document.querySelector('[data-summary="net"] [data-value]');
        const totalEl = document.querySelector('[data-summary="total"] [data-value]');

        if (incomeEl) incomeEl.textContent = 'Rp ' + FinanceUI.formatCurrency(summary.total_income);
        if (expenseEl) expenseEl.textContent = 'Rp ' + FinanceUI.formatCurrency(summary.total_expense);
        if (netEl) {
            const isNegative = summary.net_balance < 0;
            netEl.textContent = (isNegative ? '-' : '') + 'Rp ' + FinanceUI.formatCurrency(Math.abs(summary.net_balance));
            netEl.className = isNegative ? 'text-3xl font-bold tabular-nums text-red-600 mb-1' : 'text-3xl font-bold tabular-nums text-gray-900 mb-1';
        }
        if (totalEl) totalEl.textContent = 'Rp ' + FinanceUI.formatCurrency(summary.total_wallet_balance);
    },

    renderRecentTransactions(transactions) {
        const container = document.getElementById('recent-transactions-container');
        if (!container) return;

        if (!transactions || transactions.length === 0) {
            FinanceUI.showEmpty(container, 'ph-receipt', 'No Transactions Yet',
                'Start tracking your finances by adding your first transaction.',
                { url: '/transactions/create', label: 'Add Transaction' });
            return;
        }

        const rows = transactions.slice(0, 5).map(t => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="text-sm text-gray-600">${FinanceUI.formatDate(t.date)}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full ${t.type === 'income' ? 'bg-emerald-500' : 'bg-red-500'}"></div>
                        <span class="text-sm font-medium text-gray-700">${t.category_name || 'Uncategorized'}</span>
                    </div>
                </td>
                <td class="text-right tabular-nums">
                    ${t.type === 'income'
                ? `<span class="text-emerald-600 font-semibold">+${FinanceUI.formatCurrency(t.amount)}</span>`
                : `<span class="text-red-600 font-semibold">-${FinanceUI.formatCurrency(t.amount)}</span>`
            }
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
            ${transactions.length > 5 ? `
                <div class="px-6 py-3 bg-gray-50/50 border-t border-gray-100 text-center">
                    <a href="/transactions" class="text-sm text-teal-600 hover:text-teal-700 font-medium">
                        +${transactions.length - 5} more transactions
                    </a>
                </div>
            ` : ''}
        `;
    },

    renderWalletBalances(wallets) {
        const container = document.getElementById('wallet-balances-container');
        if (!container) return;

        if (!wallets || wallets.length === 0) {
            FinanceUI.showEmpty(container, 'ph-wallet', 'No Wallets Yet',
                'Create your first wallet to start managing your finances.',
                { url: '/wallets/create', label: 'Add Wallet' });
            return;
        }

        const rows = wallets.map(w => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td>
                    <div class="flex items-center gap-2">
                        <i class="ph ph-credit-card text-gray-400"></i>
                        <span class="font-medium text-gray-700">${w.wallet_name}</span>
                    </div>
                </td>
                <td class="text-right tabular-nums">
                    <span class="${(w.net_balance || 0) >= 0 ? 'text-gray-900' : 'text-red-600'} font-semibold">
                        Rp ${FinanceUI.formatCurrency(w.net_balance || 0)}
                    </span>
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Wallet</th>
                            <th class="text-right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    },

    renderExpenseByCategory(categories) {
        const container = document.getElementById('expense-by-category-container');
        if (!container) return;

        if (!categories || categories.length === 0) {
            FinanceUI.showEmpty(container, 'ph-chart-bar', 'No Expenses', 'No expenses for this period.');
            return;
        }

        const rows = categories.map(c => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                        <span class="text-gray-700">${c.category_name}</span>
                    </div>
                </td>
                <td class="text-right text-red-600 font-semibold tabular-nums">-${FinanceUI.formatCurrency(c.total_amount)}</td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    },

    renderIncomeByCategory(categories) {
        const container = document.getElementById('income-by-category-container');
        if (!container) return;

        if (!categories || categories.length === 0) {
            FinanceUI.showEmpty(container, 'ph-chart-bar', 'No Income', 'No income for this period.');
            return;
        }

        const rows = categories.map(c => `
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td>
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                        <span class="text-gray-700">${c.category_name}</span>
                    </div>
                </td>
                <td class="text-right text-emerald-600 font-semibold tabular-nums">+${FinanceUI.formatCurrency(c.total_amount)}</td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    },

    renderMonthlyTrendChart(trends) {
        const canvas = document.getElementById('monthlyTrendChart');
        if (!canvas || !trends || trends.length === 0) return;

        // Destroy existing chart
        // Destroy existing chart
        const existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        if (this.charts.trend) {
            this.charts.trend.destroy();
        }

        const labels = trends.map(t => {
            const date = new Date(t.year, t.month - 1);
            return date.toLocaleString('default', { month: 'short', year: 'numeric' });
        });

        const incomeData = trends.map(t => parseFloat(t.total_income) || 0);
        const expenseData = trends.map(t => parseFloat(t.total_expense) || 0);

        this.charts.trend = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income',
                        data: incomeData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10B981',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Expenses',
                        data: expenseData,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#EF4444',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: { usePointStyle: true, boxWidth: 8, font: { family: "'Inter', sans-serif", size: 11 } }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F3F4F6', drawBorder: false },
                        ticks: {
                            callback: function (value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                return value;
                            },
                            font: { family: "'Inter', sans-serif", size: 10 }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 10 } }
                    }
                }
            }
        });
    },

    renderCategoryBreakdownChart(categories) {
        const canvas = document.getElementById('categoryBreakdownChart');
        if (!canvas) return;

        // Destroy existing chart
        // Destroy existing chart
        const existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        if (this.charts.expenseBreakdown) {
            this.charts.expenseBreakdown.destroy();
        }

        if (!categories || categories.length === 0) {
            canvas.parentElement.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <i class="ph ph-chart-pie-slice text-4xl mb-2"></i>
                    <span class="text-sm">No expense data available</span>
                </div>
            `;
            return;
        }

        const labels = categories.map(c => c.category_name);
        const data = categories.map(c => parseFloat(c.total_amount) || 0);

        this.charts.expenseBreakdown = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#64748B'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 15, boxWidth: 8, font: { family: "'Inter', sans-serif", size: 11 } }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    },

    renderIncomeBreakdownChart(categories) {
        const canvas = document.getElementById('incomeBreakdownChart');
        if (!canvas) return;

        // Destroy existing chart
        // Destroy existing chart
        const existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        if (this.charts.incomeBreakdown) {
            this.charts.incomeBreakdown.destroy();
        }

        if (!categories || categories.length === 0) {
            canvas.parentElement.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <i class="ph ph-chart-pie-slice text-4xl mb-2"></i>
                    <span class="text-sm">No income data available</span>
                </div>
            `;
            return;
        }

        const labels = categories.map(c => c.category_name);
        const data = categories.map(c => parseFloat(c.total_amount) || 0);

        this.charts.incomeBreakdown = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#6366F1', '#14B8A6', '#F97316', '#64748B'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 15, boxWidth: 8, font: { family: "'Inter', sans-serif", size: 11 } }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
};

/**
 * Transactions Page Controller
 */
const TransactionsPage = {
    filters: {},

    async init() {
        const urlParams = new URLSearchParams(window.location.search);
        this.filters = {
            start_date: urlParams.get('start_date') || '',
            end_date: urlParams.get('end_date') || '',
            year: urlParams.get('year') || new Date().getFullYear(),
            month: urlParams.get('month') || (new Date().getMonth() + 1),
            category_id: urlParams.get('category_id') || '',
            wallet_id: urlParams.get('wallet_id') || '',
            type: urlParams.get('type') || '',
            search: urlParams.get('search') || ''
        };

        // Subscribe to store updates
        FinanceStore.subscribe(() => {
            this.loadData();
        });

        // Initial load
        await FinanceStore.init();
        this.loadData();
        this.bindEvents();
    },

    loadData() {
        const container = document.getElementById('transactions-container');
        if (!container) return;

        try {
            // Get all transactions from store
            const allTransactions = FinanceStore.getTransactions();

            if (allTransactions.length === 0 && !FinanceStore.data.initialized) {
                FinanceUI.showLoading(container);
                return;
            }

            // Filter locally
            const filtered = this.filterTransactions(allTransactions, this.filters);

            // Calculate totals locally
            const totals = this.calculateTotals(filtered);

            this.renderTransactions(filtered);
            this.renderTotals(totals);
            // this.updateFilterDropdowns(response.data); // Dropdowns should come from Store too
        } catch (error) {
            FinanceUI.showError(container, error.message || 'Failed to load transactions');
        }
    },

    filterTransactions(transactions, filters) {
        return transactions.filter(t => {
            const date = new Date(t.date);

            // Date Range
            if (filters.start_date && new Date(filters.start_date) > date) return false;
            if (filters.end_date && new Date(filters.end_date) < date) return false;

            // Month/Year (fallback if no date range)
            if (!filters.start_date && !filters.end_date) {
                if (filters.year && date.getFullYear() !== parseInt(filters.year)) return false;
                if (filters.month && (date.getMonth() + 1) !== parseInt(filters.month)) return false;
            }

            // Category
            if (filters.category_id && t.category_id != filters.category_id) return false;

            // Wallet
            if (filters.wallet_id && t.wallet_id != filters.wallet_id) return false;

            // Type
            if (filters.type && t.type !== filters.type) return false;

            // Search
            if (filters.search) {
                const search = filters.search.toLowerCase();
                const match = (t.notes && t.notes.toLowerCase().includes(search)) ||
                    (t.category_name && t.category_name.toLowerCase().includes(search)) ||
                    (t.wallet_name && t.wallet_name.toLowerCase().includes(search));
                if (!match) return false;
            }

            return true;
        });
    },

    calculateTotals(transactions) {
        let income = 0;
        let expense = 0;

        transactions.forEach(t => {
            const amount = parseFloat(t.amount);
            if (t.type === 'income') income += amount;
            else expense += amount;
        });

        return {
            count: transactions.length,
            income: income,
            expense: expense,
            net: income - expense
        };
    },

    renderTransactions(transactions) {
        const container = document.getElementById('transactions-container');
        if (!container) return;

        if (!transactions || transactions.length === 0) {
            FinanceUI.showEmpty(container, 'ph-receipt', 'No Transactions',
                'No transactions found for the selected filters.',
                { url: '/transactions/create', label: 'Add Transaction' });
            return;
        }

        const rows = transactions.map(t => `
            <tr class="hover:bg-gray-50 transition-colors" data-id="${t.id}">
                <td class="px-4 py-3">
                    <span class="text-sm text-gray-600">${FinanceUI.formatDate(t.date, 'short')}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <span class="badge ${t.type === 'income' ? 'badge-success' : 'badge-danger'}">
                            ${t.type === 'income' ? 'Income' : 'Expense'}
                        </span>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="font-medium text-gray-800">${t.category_name || 'Uncategorized'}</span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-gray-600">${t.wallet_name || '-'}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <span class="font-semibold tabular-nums ${t.type === 'income' ? 'text-emerald-600' : 'text-red-600'}">
                        ${t.type === 'income' ? '+' : '-'}Rp ${FinanceUI.formatCurrency(t.amount)}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="text-sm text-gray-500 truncate max-w-xs block">${t.notes || '-'}</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="/transactions/edit/${t.id}" class="p-2 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Edit">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <button onclick="TransactionsPage.deleteTransaction(${t.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        container.innerHTML = `
            <div class="overflow-x-auto">
                <table class="table-custom w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Type</th>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Wallet</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                            <th class="px-4 py-3 text-left">Notes</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    },

    renderTotals(totals) {
        const countEl = document.getElementById('transaction-count');
        const incomeEl = document.getElementById('total-income');
        const expenseEl = document.getElementById('total-expense');
        const netEl = document.getElementById('total-net');

        if (countEl) countEl.textContent = totals.count + ' transactions';
        if (incomeEl) incomeEl.textContent = 'Rp ' + FinanceUI.formatCurrency(totals.income);
        if (expenseEl) expenseEl.textContent = 'Rp ' + FinanceUI.formatCurrency(totals.expense);
        if (netEl) {
            netEl.textContent = (totals.net >= 0 ? '+' : '') + 'Rp ' + FinanceUI.formatCurrency(totals.net);
            netEl.className = totals.net >= 0 ? 'text-emerald-600 font-semibold' : 'text-red-600 font-semibold';
        }
    },

    updateFilterDropdowns(data) {
        // Populate category and wallet dropdowns if needed
    },

    bindEvents() {
        // Filter form submission
        const filterForm = document.getElementById('filter-form');
        if (filterForm) {
            // Remove existing listener if any (to prevent duplicates)
            if (this.filterSubmitHandler) {
                filterForm.removeEventListener('submit', this.filterSubmitHandler);
            }

            // Create new handler
            this.filterSubmitHandler = (e) => {
                e.preventDefault();
                this.applyFilters();
            };

            filterForm.addEventListener('submit', this.filterSubmitHandler);
        }
    },

    applyFilters() {
        const form = document.getElementById('filter-form');
        if (!form) return;

        const formData = new FormData(form);
        this.filters = Object.fromEntries(formData.entries());

        // Update URL
        const params = new URLSearchParams(this.filters);
        window.history.pushState({}, '', '/transactions?' + params.toString());

        this.loadData();
    },

    async deleteTransaction(id) {
        const confirmed = await FinanceUI.confirm(
            'Delete Transaction?',
            'This action cannot be undone.',
            'Delete',
            'Cancel'
        );

        if (confirmed) {
            try {
                const response = await FinanceAPI.deleteTransaction(id);
                if (response.success) {
                    FinanceUI.showToast('Transaction deleted successfully');
                    await FinanceStore.sync(); // Sync to get updates
                    // loadData is called automatically via subscription, but we can call it to be sure
                }
            } catch (error) {
                FinanceUI.showToast('Failed to delete transaction', 'error');
            }
        }
    }
};

/**
 * Wallets Page Controller
 */
const WalletsPage = {
    async init() {
        // Subscribe to store updates
        FinanceStore.subscribe(() => {
            this.loadData();
        });

        // Initial load
        await FinanceStore.init();
        this.loadData();
    },

    loadData() {
        const container = document.getElementById('wallets-container');
        if (!container) return;

        try {
            if (!FinanceStore.data.initialized) {
                FinanceUI.showLoading(container);
                return;
            }

            const wallets = FinanceStore.getWallets();
            // Calculate total balance locally
            const totalBalance = wallets.reduce((sum, w) => sum + parseFloat(w.balance || 0), 0);

            this.renderWallets(wallets);
            this.renderTotalBalance(totalBalance);
        } catch (error) {
            FinanceUI.showError(container, 'Failed to load wallets');
        }
    },

    renderWallets(wallets) {
        const container = document.getElementById('wallets-container');
        if (!container) return;

        if (!wallets || wallets.length === 0) {
            FinanceUI.showEmpty(container, 'ph-wallet', 'No Wallets',
                'Create your first wallet to start tracking your finances.',
                { url: '/wallets/create', label: 'Create Wallet' });
            return;
        }

        const cards = wallets.map(w => `
            <div class="card-custom p-6" data-id="${w.id}">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center">
                        <i class="ph-fill ph-wallet text-teal-600 text-2xl"></i>
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="/wallets/edit/${w.id}" class="p-2 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Edit">
                            <i class="ph ph-pencil-simple"></i>
                        </a>
                        <button onclick="WalletsPage.deleteWallet(${w.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">${w.name}</h3>
                <p class="text-sm text-gray-500 mb-4">${w.description || 'No description'}</p>
                <div class="pt-4 border-t border-gray-100">
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Balance</span>
                    <p class="text-2xl font-bold tabular-nums ${(w.balance || 0) >= 0 ? 'text-gray-900' : 'text-red-600'}">
                        Rp ${FinanceUI.formatCurrency(w.balance || 0)}
                    </p>
                </div>
            </div>
        `).join('');

        container.innerHTML = `<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">${cards}</div>`;
    },

    renderTotalBalance(total) {
        const el = document.getElementById('total-wallet-balance');
        if (el) el.textContent = 'Rp ' + FinanceUI.formatCurrency(total);
    },

    async deleteWallet(id) {
        const confirmed = await FinanceUI.confirm(
            'Delete Wallet?',
            'Make sure there are no transactions in this wallet.',
            'Delete',
            'Cancel'
        );

        if (confirmed) {
            try {
                const response = await FinanceAPI.deleteWallet(id);
                if (response.success) {
                    FinanceUI.showToast('Wallet deleted successfully');
                    await FinanceStore.sync();
                }
            } catch (error) {
                FinanceUI.showToast(error.message || 'Failed to delete wallet', 'error');
            }
        }
    }
};

/**
 * Categories Page Controller
 */
const CategoriesPage = {
    async init() {
        await this.loadData();
    },

    async loadData() {
        const container = document.getElementById('categories-container');
        if (!container) return;

        try {
            FinanceUI.showLoading(container);
            const response = await FinanceAPI.getCategories();

            if (response.success) {
                this.renderCategories(response.data);
            }
        } catch (error) {
            FinanceUI.showError(container, 'Failed to load categories');
        }
    },

    renderCategories(data) {
        const container = document.getElementById('categories-container');
        if (!container) return;

        if (!data.categories || data.categories.length === 0) {
            FinanceUI.showEmpty(container, 'ph-tag', 'No Categories',
                'Create categories to organize your transactions.',
                { url: '/categories/create', label: 'Create Category' });
            return;
        }

        const renderTable = (categories, type) => {
            if (categories.length === 0) return '<p class="text-gray-500 text-sm p-4">No categories</p>';

            const rows = categories.map(c => `
                <tr class="hover:bg-gray-50 transition-colors" data-id="${c.id}">
                    <td class="px-4 py-3">
                        <span class="font-medium text-gray-800">${c.name}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="badge badge-secondary">${c.usage_count || 0} transactions</span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="/categories/edit/${c.id}" class="p-2 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Edit">
                                <i class="ph ph-pencil-simple"></i>
                            </a>
                            <button onclick="CategoriesPage.deleteCategory(${c.id})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <i class="ph ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            return `
                <table class="table-custom w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Usage</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            `;
        };

        container.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="card-custom overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="ph-fill ph-arrow-circle-down text-emerald-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Income Categories</h3>
                            <span class="badge badge-success ml-auto">${data.income_categories.length}</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">${renderTable(data.income_categories, 'income')}</div>
                </div>
                <div class="card-custom overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-red-50/50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="ph-fill ph-arrow-circle-up text-red-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Expense Categories</h3>
                            <span class="badge badge-danger ml-auto">${data.expense_categories.length}</span>
                        </div>
                    </div>
                    <div class="overflow-x-auto">${renderTable(data.expense_categories, 'expense')}</div>
                </div>
            </div>
        `;
    },

    async deleteCategory(id) {
        const confirmed = await FinanceUI.confirm(
            'Delete Category?',
            'Make sure there are no transactions using this category.',
            'Delete',
            'Cancel'
        );

        if (confirmed) {
            try {
                const response = await FinanceAPI.deleteCategory(id);
                if (response.success) {
                    FinanceUI.showToast('Category deleted successfully');
                    this.loadData();
                }
            } catch (error) {
                FinanceUI.showToast(error.message || 'Failed to delete category', 'error');
            }
        }
    }
};

// Alpine.js Component for Categories
function categoryApp(initialCategories = []) {
    return {
        loading: false,
        submitting: false,
        categories: initialCategories,
        activeTab: 'all', // 'all', 'income', 'expense'
        showModal: false,
        modalTitle: 'Add Category',
        isEditMode: false,
        editId: null,
        formName: '',
        formType: '',

        get filteredCategories() {
            if (this.activeTab === 'all') {
                return this.categories;
            }
            return this.categories.filter(c => c.type === this.activeTab);
        },

        async init() {
            // Subscribe to store updates
            FinanceStore.subscribe(() => {
                this.categories = FinanceStore.getCategories();
            });

            // Initial load
            await FinanceStore.init();
            this.categories = FinanceStore.getCategories();
        },

        async loadCategories() {
            // Deprecated: Data is managed by FinanceStore
            // But we might want to force a refresh?
            await FinanceStore.sync();
        },

        openCreateModal() {
            this.isEditMode = false;
            this.editId = null;
            this.modalTitle = 'Add Category';
            this.formName = '';
            this.formType = '';
            this.showModal = true;
        },

        openEditModal(category) {
            this.isEditMode = true;
            this.editId = category.id;
            this.modalTitle = 'Edit Category';
            this.formName = category.name;
            this.formType = category.type;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        async submitForm() {
            if (!this.formName.trim() || !this.formType) {
                FinanceUI.showToast('Please fill all fields', 'error');
                return;
            }

            this.submitting = true;
            const url = this.isEditMode
                ? `/api/categories/edit/${this.editId}`
                : '/api/categories/create';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: this.formName,
                        type: this.formType,
                        csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
                    })
                });
                const data = await response.json();

                if (data.success) {
                    FinanceUI.showToast(this.isEditMode ? 'Category updated' : 'Category created', 'success');
                    this.closeModal();
                    await this.loadCategories();
                } else {
                    throw new Error(data.message || 'Operation failed');
                }
            } catch (error) {
                FinanceUI.showToast(error.message || 'Operation failed', 'error');
            } finally {
                this.submitting = false;
            }
        },

        async deleteCategory(id, usageCount) {
            if (usageCount > 0) {
                const result = await Swal.fire({
                    title: 'Cannot Delete',
                    text: `This category has ${usageCount} transaction(s). Transfer them first or delete them manually.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Transfer Transactions',
                    cancelButtonText: 'Cancel'
                });

                if (result.isConfirmed) {
                    window.location.href = `/categories/delete/${id}`;
                }
                return;
            }

            const confirmed = await Swal.fire({
                title: 'Delete Category?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            });

            if (confirmed.isConfirmed) {
                try {
                    const response = await fetch(`/api/categories/delete/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
                        })
                    });
                    const data = await response.json();

                    if (data.success) {
                        FinanceUI.showToast('Category deleted', 'success');
                        await this.loadCategories();
                    } else {
                        throw new Error(data.message || 'Failed to delete');
                    }
                } catch (error) {
                    FinanceUI.showToast(error.message || 'Failed to delete', 'error');
                }
            }
        }
    };
}

/**
 * Page Router - Initialize correct page controller
 */
const PageRouter = {
    init() {
        const path = window.location.pathname;

        if (path === '/dashboard' || path === '/') {
            if (document.getElementById('dashboard-page')) {
                DashboardPage.init();
            }
        } else if (path === '/transactions' || path.startsWith('/transactions')) {
            if (document.getElementById('transactions-page')) {
                TransactionsPage.init();
            }
        } else if (path === '/wallets' || path.startsWith('/wallets')) {
            if (document.getElementById('wallets-page')) {
                WalletsPage.init();
            }
        }
        // Categories page uses Alpine.js (categoryApp) which initializes automatically
    }
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    PageRouter.init();
});


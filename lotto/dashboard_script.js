// Lottery Dashboard JavaScript
class LotteryDashboard {
    constructor() {
        this.currentGame = 1;
        this.charts = {};
        this.data = {};
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadDashboardData();
        this.setupCharts();
    }

    setupEventListeners() {
        // Game selector
        document.getElementById('gameSelect').addEventListener('change', (e) => {
            this.currentGame = parseInt(e.target.value);
            this.loadDashboardData();
        });

        // Refresh button
        document.getElementById('refreshBtn').addEventListener('click', () => {
            this.loadDashboardData();
        });

        // Prediction button
        document.getElementById('generatePrediction').addEventListener('click', () => {
            this.generatePrediction();
        });
    }

    async loadDashboardData() {
        try {
            this.showLoading();
            
            // Load data from backend API
            const response = await fetch(`/api/lottery-data?game=${this.currentGame}`);
            
            if (!response.ok) {
                // If API is not available, use mock data
                this.data = this.getMockData();
            } else {
                this.data = await response.json();
            }
            
            this.updateDashboard();
            this.hideLoading();
        } catch (error) {
            console.warn('API not available, using mock data');
            this.data = this.getMockData();
            this.updateDashboard();
            this.hideLoading();
        }
    }

    getMockData() {
        return {
            lastDrawDate: '2024-01-15',
            nextDrawDate: '2024-01-17',
            totalDraws: 3245,
            recentDraws: [
                { date: '2024-01-15', numbers: [8, 15, 23, 31, 42] },
                { date: '2024-01-13', numbers: [5, 12, 28, 35, 41] },
                { date: '2024-01-11', numbers: [3, 18, 25, 33, 39] },
                { date: '2024-01-09', numbers: [7, 14, 21, 29, 36] },
                { date: '2024-01-07', numbers: [2, 16, 24, 32, 40] }
            ],
            frequency: {
                numbers: Array.from({length: 42}, (_, i) => i + 1),
                counts: Array.from({length: 42}, () => Math.floor(Math.random() * 100) + 20)
            },
            hotNumbers: [23, 31, 15, 42, 8],
            coldNumbers: [1, 6, 11, 17, 22],
            sumAnalysis: {
                average: 119.5,
                mostCommon: 115,
                range: [65, 175]
            },
            evenOddAnalysis: {
                evenCount: 52,
                oddCount: 48,
                ratio: 1.08
            },
            sumDistribution: {
                ranges: ['60-80', '81-100', '101-120', '121-140', '141-160', '161-180'],
                counts: [45, 125, 245, 195, 85, 25]
            }
        };
    }

    updateDashboard() {
        this.updateStatCards();
        this.updateRecentDraws();
        this.updateHotColdNumbers();
        this.updateSumAnalysis();
        this.updateEvenOddAnalysis();
        this.updateCharts();
    }

    updateStatCards() {
        document.getElementById('lastDrawDate').textContent = this.data.lastDrawDate;
        document.getElementById('nextDrawDate').textContent = this.data.nextDrawDate;
        document.getElementById('totalDraws').textContent = this.data.totalDraws.toLocaleString();
        document.getElementById('hotNumbers').textContent = this.data.hotNumbers.slice(0, 3).join(', ');
    }

    updateRecentDraws() {
        const container = document.getElementById('recentDraws');
        container.innerHTML = '';

        this.data.recentDraws.forEach(draw => {
            const drawElement = document.createElement('div');
            drawElement.className = 'draw-item';
            
            const numbersContainer = document.createElement('div');
            numbersContainer.className = 'draw-numbers';
            
            draw.numbers.forEach(number => {
                const numberElement = document.createElement('div');
                numberElement.className = 'draw-number';
                numberElement.textContent = number;
                numbersContainer.appendChild(numberElement);
            });
            
            const dateElement = document.createElement('div');
            dateElement.className = 'draw-date';
            dateElement.textContent = this.formatDate(draw.date);
            
            drawElement.appendChild(numbersContainer);
            drawElement.appendChild(dateElement);
            container.appendChild(drawElement);
        });
    }

    updateHotColdNumbers() {
        const hotContainer = document.getElementById('hotNumbersList');
        const coldContainer = document.getElementById('coldNumbersList');
        
        hotContainer.innerHTML = '';
        coldContainer.innerHTML = '';

        this.data.hotNumbers.forEach(number => {
            const numberElement = document.createElement('div');
            numberElement.className = 'number-item hot-number';
            numberElement.textContent = number;
            hotContainer.appendChild(numberElement);
        });

        this.data.coldNumbers.forEach(number => {
            const numberElement = document.createElement('div');
            numberElement.className = 'number-item cold-number';
            numberElement.textContent = number;
            coldContainer.appendChild(numberElement);
        });
    }

    updateSumAnalysis() {
        document.getElementById('avgSum').textContent = this.data.sumAnalysis.average.toFixed(1);
        document.getElementById('commonSum').textContent = this.data.sumAnalysis.mostCommon;
        document.getElementById('sumRange').textContent = `${this.data.sumAnalysis.range[0]} - ${this.data.sumAnalysis.range[1]}`;
    }

    updateEvenOddAnalysis() {
        document.getElementById('evenCount').textContent = `${this.data.evenOddAnalysis.evenCount}%`;
        document.getElementById('oddCount').textContent = `${this.data.evenOddAnalysis.oddCount}%`;
        document.getElementById('eoRatio').textContent = this.data.evenOddAnalysis.ratio.toFixed(2);
    }

    setupCharts() {
        this.setupFrequencyChart();
        this.setupSumChart();
        this.setupEvenOddChart();
    }

    setupFrequencyChart() {
        const ctx = document.getElementById('frequencyChart').getContext('2d');
        this.charts.frequency = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Frequency',
                    data: [],
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    setupSumChart() {
        const ctx = document.getElementById('sumChart').getContext('2d');
        this.charts.sum = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Sum Distribution',
                    data: [],
                    borderColor: 'rgba(118, 75, 162, 1)',
                    backgroundColor: 'rgba(118, 75, 162, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    setupEvenOddChart() {
        const ctx = document.getElementById('evenOddChart').getContext('2d');
        this.charts.evenOdd = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Even', 'Odd'],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)'
                    ],
                    borderColor: [
                        'rgba(102, 126, 234, 1)',
                        'rgba(118, 75, 162, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    updateCharts() {
        // Update frequency chart
        this.charts.frequency.data.labels = this.data.frequency.numbers;
        this.charts.frequency.data.datasets[0].data = this.data.frequency.counts;
        this.charts.frequency.update();

        // Update sum chart
        this.charts.sum.data.labels = this.data.sumDistribution.ranges;
        this.charts.sum.data.datasets[0].data = this.data.sumDistribution.counts;
        this.charts.sum.update();

        // Update even/odd chart
        this.charts.evenOdd.data.datasets[0].data = [
            this.data.evenOddAnalysis.evenCount,
            this.data.evenOddAnalysis.oddCount
        ];
        this.charts.evenOdd.update();
    }

    generatePrediction() {
        const method = document.getElementById('predictionMethod').value;
        const resultsContainer = document.getElementById('predictionResults');
        
        // Show loading
        resultsContainer.innerHTML = '<div class="loading"></div>';
        
        // Simulate API call delay
        setTimeout(() => {
            const prediction = this.calculatePrediction(method);
            this.displayPrediction(prediction);
        }, 1000);
    }

    calculatePrediction(method) {
        let prediction = [];
        
        switch (method) {
            case 'frequency':
                // Use most frequent numbers with some randomization
                const sortedByFreq = this.data.frequency.numbers
                    .map((num, idx) => ({ number: num, freq: this.data.frequency.counts[idx] }))
                    .sort((a, b) => b.freq - a.freq);
                
                prediction = sortedByFreq.slice(0, 8).map(item => item.number);
                prediction = this.shuffleArray(prediction).slice(0, 5).sort((a, b) => a - b);
                break;
                
            case 'pattern':
                // Generate based on patterns from recent draws
                const recentNumbers = this.data.recentDraws.flatMap(draw => draw.numbers);
                const numberCount = {};
                
                recentNumbers.forEach(num => {
                    numberCount[num] = (numberCount[num] || 0) + 1;
                });
                
                const patternNumbers = Object.keys(numberCount)
                    .filter(num => numberCount[num] >= 2)
                    .map(num => parseInt(num));
                
                prediction = patternNumbers.slice(0, 5);
                while (prediction.length < 5) {
                    const randomNum = Math.floor(Math.random() * 42) + 1;
                    if (!prediction.includes(randomNum)) {
                        prediction.push(randomNum);
                    }
                }
                prediction.sort((a, b) => a - b);
                break;
                
            case 'statistical':
                // Use statistical analysis
                const avg = this.data.sumAnalysis.average;
                const target = avg + (Math.random() - 0.5) * 20;
                
                prediction = this.generateNumbersForSum(target);
                break;
                
            default:
                prediction = [8, 15, 23, 31, 42];
        }
        
        return prediction;
    }

    generateNumbersForSum(targetSum) {
        const numbers = [];
        let currentSum = 0;
        
        for (let i = 0; i < 5; i++) {
            const remaining = 5 - i;
            const maxPossible = 42 * remaining;
            const minPossible = remaining;
            const needed = targetSum - currentSum;
            
            let min = Math.max(1, Math.ceil(needed / remaining));
            let max = Math.min(42, Math.floor(needed - minPossible + 1));
            
            if (max < min) max = min;
            
            let num;
            do {
                num = Math.floor(Math.random() * (max - min + 1)) + min;
            } while (numbers.includes(num));
            
            numbers.push(num);
            currentSum += num;
        }
        
        return numbers.sort((a, b) => a - b);
    }

    displayPrediction(prediction) {
        const resultsContainer = document.getElementById('predictionResults');
        resultsContainer.innerHTML = '';
        
        const numbersContainer = document.createElement('div');
        numbersContainer.className = 'prediction-numbers';
        
        prediction.forEach((number, index) => {
            const numberElement = document.createElement('div');
            numberElement.className = 'prediction-number';
            numberElement.textContent = number;
            numberElement.style.animationDelay = `${index * 0.1}s`;
            numbersContainer.appendChild(numberElement);
        });
        
        resultsContainer.appendChild(numbersContainer);
        
        const infoText = document.createElement('p');
        infoText.textContent = 'Generated prediction based on selected method';
        infoText.style.marginTop = '1rem';
        infoText.style.fontSize = '0.9rem';
        infoText.style.color = '#718096';
        resultsContainer.appendChild(infoText);
    }

    shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }
        return shuffled;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    showLoading() {
        document.getElementById('refreshBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    }

    hideLoading() {
        document.getElementById('refreshBtn').innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', () => {
    new LotteryDashboard();
});

// Add some utility functions for enhanced functionality
class LotteryAnalytics {
    static calculateTrends(recentDraws) {
        const trends = {
            increasingNumbers: [],
            decreasingNumbers: [],
            stableNumbers: []
        };
        
        // Analyze number trends over recent draws
        const numberAppearances = {};
        
        recentDraws.forEach((draw, drawIndex) => {
            draw.numbers.forEach(number => {
                if (!numberAppearances[number]) {
                    numberAppearances[number] = [];
                }
                numberAppearances[number].push(drawIndex);
            });
        });
        
        Object.keys(numberAppearances).forEach(number => {
            const appearances = numberAppearances[number];
            if (appearances.length >= 3) {
                const recentAppearances = appearances.slice(-3);
                const trend = this.calculateTrend(recentAppearances);
                
                if (trend > 0.1) {
                    trends.increasingNumbers.push(parseInt(number));
                } else if (trend < -0.1) {
                    trends.decreasingNumbers.push(parseInt(number));
                } else {
                    trends.stableNumbers.push(parseInt(number));
                }
            }
        });
        
        return trends;
    }
    
    static calculateTrend(appearances) {
        if (appearances.length < 2) return 0;
        
        const intervals = [];
        for (let i = 1; i < appearances.length; i++) {
            intervals.push(appearances[i] - appearances[i-1]);
        }
        
        const avgInterval = intervals.reduce((a, b) => a + b, 0) / intervals.length;
        return avgInterval > 0 ? 1 / avgInterval : 0;
    }
    
    static calculatePairFrequency(recentDraws) {
        const pairs = {};
        
        recentDraws.forEach(draw => {
            const numbers = draw.numbers.sort((a, b) => a - b);
            
            for (let i = 0; i < numbers.length; i++) {
                for (let j = i + 1; j < numbers.length; j++) {
                    const pair = `${numbers[i]}-${numbers[j]}`;
                    pairs[pair] = (pairs[pair] || 0) + 1;
                }
            }
        });
        
        return Object.entries(pairs)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10)
            .map(([pair, count]) => ({ pair, count }));
    }
}
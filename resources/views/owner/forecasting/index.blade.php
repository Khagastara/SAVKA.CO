{{-- owner/forecasting/index.blade.php --}}
@extends('layouts.owner')

@section('title', 'AI Forecasting - Prediksi Permintaan')

@section('header', 'AI Forecasting System')

@section('styles')
<style>
    .forecasting-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 30px;
        margin-top: 20px;
    }

    .prediction-control {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        height: fit-content;
        position: sticky;
        top: 30px;
    }

    .control-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: var(--charcoal-gray);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ai-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        font-weight: 600;
        color: var(--charcoal-gray);
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--mocha-cream);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--sage-green);
        box-shadow: 0 0 0 4px rgba(183, 196, 164, 0.1);
    }

    .btn-predict {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--sage-green) 0%, #8fa67a 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .btn-predict:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(183, 196, 164, 0.4);
    }

    .btn-predict:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .btn-predict.processing {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .processing-animation {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        to {
            left: 100%;
        }
    }

    .results-container {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .results-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    .results-meta {
        font-size: 0.9rem;
        color: #666;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .prediction-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 16px;
        transition: all 0.3s;
        animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .prediction-card:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }

    .product-info {
        flex: 1;
    }

    .product-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--charcoal-gray);
        margin-bottom: 4px;
    }

    .product-meta {
        font-size: 0.9rem;
        color: #666;
    }

    .accuracy-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .accuracy-high {
        background: #d4edda;
        color: #155724;
    }

    .accuracy-medium {
        background: #fff3cd;
        color: #856404;
    }

    .accuracy-low {
        background: #f8d7da;
        color: #721c24;
    }

    .prediction-value {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid rgba(255, 255, 255, 0.5);
    }

    .value-label {
        font-size: 0.9rem;
        color: #666;
    }

    .value-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--charcoal-gray);
    }

    .value-unit {
        font-size: 0.9rem;
        color: #666;
    }

    .ai-thinking {
        text-align: center;
        padding: 40px;
    }

    .thinking-dots {
        display: inline-block;
        margin: 20px 0;
    }

    .thinking-dots span {
        display: inline-block;
        width: 12px;
        height: 12px;
        background: var(--sage-green);
        border-radius: 50%;
        margin: 0 4px;
        animation: thinking 1.4s infinite ease-in-out;
    }

    .thinking-dots span:nth-child(1) {
        animation-delay: -0.32s;
    }

    .thinking-dots span:nth-child(2) {
        animation-delay: -0.16s;
    }

    @keyframes thinking {
        0%, 80%, 100% {
            transform: scale(0.8);
            opacity: 0.5;
        }
        40% {
            transform: scale(1.2);
            opacity: 1;
        }
    }

    .info-box {
        background: #e7f3ff;
        border-left: 4px solid #2196F3;
        padding: 16px;
        border-radius: 8px;
        margin-top: 20px;
        font-size: 0.9rem;
        color: #0d47a1;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .stat-card {
        text-align: center;
        padding: 16px;
        background: linear-gradient(135deg, var(--peach) 0%, var(--mocha-cream) 100%);
        border-radius: 12px;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--charcoal-gray);
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
    }

    .btn-export {
        padding: 10px 20px;
        background: white;
        border: 2px solid var(--sage-green);
        color: var(--sage-green);
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-export:hover {
        background: var(--sage-green);
        color: white;
    }

    .note-warning {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 10px 12px;
        margin-top: 10px;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #856404;
    }
</style>
@endsection

@section('content')
<div class="forecasting-container">
    <!-- Control Panel -->
    <div class="prediction-control">
        <div class="control-title">
            <span>AI Prediction Control</span>
            <span class="ai-badge">AI</span>
        </div>

        <form id="forecastForm">
            <div class="form-group">
                <label class="form-label">Jumlah Minggu Historis</label>
                <input type="number"
                       id="weekUsed"
                       class="form-input"
                       min="2"
                       max="6"
                       value="4"
                       required>
                <small style="color: #666; font-size: 0.85rem;">Gunakan 2-6 minggu data historis</small>
            </div>

            <button type="submit" id="btnPredict" class="btn-predict">
                <span id="btnText">Mulai Prediksi AI</span>
            </button>
        </form>

        <div class="info-box">
            <strong>Cara Kerja:</strong><br>
            Sistem AI akan menganalisis data permintaan historis menggunakan metode Single Moving Average untuk memprediksi permintaan produk di masa mendatang.
        </div>
    </div>

    <!-- Results Panel -->
    <div class="results-container">
        <div class="results-header">
            <div class="results-title">Hasil Prediksi</div>
            <button class="btn-export" id="btnExport" style="display: none;">
                Export Data
            </button>
        </div>

        <div id="resultsContent">
            <div class="empty-state">
                <h3>Siap Melakukan Prediksi</h3>
                <p>Atur parameter dan klik tombol "Mulai Prediksi AI" untuk memulai analisis</p>
            </div>
        </div>

        <div id="statsSection" style="display: none;">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value" id="totalProducts">0</div>
                    <div class="stat-label">Produk Dianalisis</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="avgAccuracy">0%</div>
                    <div class="stat-label">Rata-rata Akurasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="totalDemand">0</div>
                    <div class="stat-label">Total Prediksi Permintaan</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forecastForm');
    const btnPredict = document.getElementById('btnPredict');
    const btnText = document.getElementById('btnText');
    const resultsContent = document.getElementById('resultsContent');
    const statsSection = document.getElementById('statsSection');
    const btnExport = document.getElementById('btnExport');

    let forecastData = [];

    // Load existing forecasting data on page load
    loadForecasts();

    async function loadForecasts() {
        try {
            const response = await fetch('/forecasting', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load forecasts');
            }

            const result = await response.json();

            if (result.data && result.data.length > 0) {
                forecastData = result.data;
                displayExistingForecasts(result.data);
                displayExistingStats(result.data);
            }
        } catch (error) {
            console.log('No previous forecast data:', error);
        }
    }

    function displayExistingForecasts(data) {
        let html = '';
        data.forEach((forecast, index) => {
            const accuracyClass = forecast.accurancy >= 80 ? 'accuracy-high' :
                                 forecast.accurancy >= 60 ? 'accuracy-medium' : 'accuracy-low';

            html += `
                <div class="prediction-card" style="animation-delay: ${index * 0.1}s">
                    <div class="card-header">
                        <div class="product-info">
                            <div class="product-name">${forecast.product_name || 'Produk'}</div>
                            <div class="product-meta">
                                Warna: ${forecast.product_color || 'N/A'} • Ukuran: ${forecast.product_size || 'N/A'}
                            </div>
                            <div style="font-size: 0.85rem; color: #666; margin-top: 4px;">
                                ${new Date(forecast.forecast_date).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'short',
                                    year: 'numeric'
                                })} • ${forecast.week_used} minggu data
                            </div>
                        </div>
                        <div class="accuracy-badge ${accuracyClass}">
                            ${forecast.accurancy}% Akurat
                        </div>
                    </div>
                    <div class="prediction-value">
                        <span class="value-label">Prediksi Permintaan:</span>
                        <span class="value-number">${forecast.predicted_demand}</span>
                        <span class="value-unit">unit</span>
                    </div>
                </div>
            `;
        });

        resultsContent.innerHTML = html;
        btnExport.style.display = 'block';
    }

    function displayExistingStats(data) {
        if (data.length === 0) return;

        const totalProducts = data.length;
        const avgAccuracy = Math.round(
            data.reduce((sum, item) => sum + item.accurancy, 0) / data.length
        );
        const totalDemand = data.reduce((sum, item) => sum + item.predicted_demand, 0);

        document.getElementById('totalProducts').textContent = totalProducts;
        document.getElementById('avgAccuracy').textContent = avgAccuracy + '%';
        document.getElementById('totalDemand').textContent = totalDemand;

        statsSection.style.display = 'block';
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const weekUsed = document.getElementById('weekUsed').value;

        if (!weekUsed || weekUsed < 2 || weekUsed > 6) {
            showError('Jumlah minggu harus antara 2-6');
            return;
        }

        showThinkingState();

        try {
            const response = await fetch('/forecasting/calculate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    week_used: parseInt(weekUsed)
                })
            });

            const result = await response.json();

            if (response.ok && result.data) {
                forecastData = result.data;

                setTimeout(() => {
                    displayResults(result.data, result.skipped_products, result.summary);
                    displayStats(result.data, result.summary);
                }, 1500);
            } else {
                setTimeout(() => {
                    if (result.skipped_products && result.summary) {
                        showDetailedError(result.message, result.skipped_products, result.summary);
                    } else {
                        showError(result.message || 'Terjadi kesalahan saat melakukan prediksi');
                    }
                }, 1000);
            }
        } catch (error) {
            console.error('Error:', error);
            setTimeout(() => {
                showError('Gagal terhubung ke server. Pastikan endpoint tersedia.');
            }, 1000);
        }
    });

    function showThinkingState() {
        btnPredict.disabled = true;
        btnPredict.classList.add('processing');
        btnText.innerHTML = 'AI Sedang Berpikir...';
        btnPredict.innerHTML = btnText.outerHTML + '<div class="processing-animation"></div>';

        resultsContent.innerHTML = `
            <div class="ai-thinking">
                <h3>AI Sedang Menganalisis Data...</h3>
                <div class="thinking-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <p style="color: #666;">Memproses data historis dan menghitung prediksi</p>
            </div>
        `;

        statsSection.style.display = 'none';
        btnExport.style.display = 'none';
    }

    function displayResults(data, skippedProducts, summary) {
        if (data.length === 0) {
            if (skippedProducts && summary) {
                showDetailedError('Tidak ada produk yang dapat dianalisis', skippedProducts, summary);
            } else {
                resultsContent.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">⚠️</div>
                        <h3>Data Tidak Mencukupi</h3>
                        <p>Tidak ada cukup data historis untuk melakukan prediksi</p>
                    </div>
                `;
            }
            resetButton();
            return;
        }

        let html = '';
        data.forEach((item, index) => {
            const accuracyClass = item.accuracy >= 80 ? 'accuracy-high' :
                                 item.accuracy >= 60 ? 'accuracy-medium' : 'accuracy-low';

            html += `
                <div class="prediction-card" style="animation-delay: ${index * 0.1}s">
                    <div class="card-header">
                        <div class="product-info">
                            <div class="product-name">${item.product}</div>
                            <div class="product-meta">
                                Warna: ${item.product_color} • Ukuran: ${item.size}
                            </div>
                            ${item.note ? `
                                <div class="note-warning">
                                    ${item.note}
                                </div>
                            ` : ''}
                        </div>
                        <div class="accuracy-badge ${accuracyClass}">
                            ${item.accuracy}% Akurat
                        </div>
                    </div>
                    <div class="prediction-value">
                        <span class="value-label">Prediksi Permintaan:</span>
                        <span class="value-number">${item.predicted_demand}</span>
                        <span class="value-unit">unit</span>
                    </div>
                    <div style="margin-top: 12px; font-size: 0.85rem; color: #666;">
                        Total permintaan: ${item.total_demand} • Rata-rata: ${item.average_demand} • Minggu: ${item.weeks_analyzed}
                    </div>
                </div>
            `;
        });

        resultsContent.innerHTML = html;

        // Show skipped products warning if any
        if (skippedProducts && skippedProducts.length > 0) {
            showSkippedInfo(skippedProducts, summary);
        }

        resetButton();
        btnExport.style.display = 'block';
    }

    function displayStats(data, summary) {
        if (data.length === 0) return;

        const totalProducts = summary ? summary.processed : data.length;
        const avgAccuracy = summary && summary.average_accuracy ?
            summary.average_accuracy :
            Math.round(data.reduce((sum, item) => sum + item.accuracy, 0) / data.length);
        const totalDemand = data.reduce((sum, item) => sum + item.predicted_demand, 0);

        document.getElementById('totalProducts').textContent = totalProducts;
        document.getElementById('avgAccuracy').textContent = avgAccuracy + '%';
        document.getElementById('totalDemand').textContent = totalDemand;

        statsSection.style.display = 'block';
    }

    function showError(message) {
        resultsContent.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">❌</div>
                <h3>Terjadi Kesalahan</h3>
                <p>${message}</p>
            </div>
        `;
        resetButton();
    }

    function showDetailedError(message, skippedProducts, summary) {
        let skippedHtml = '';
        if (skippedProducts && skippedProducts.length > 0) {
            skippedHtml = '<div style="margin-top: 20px; text-align: left; max-height: 300px; overflow-y: auto;">';
            skippedHtml += '<h4 style="margin-bottom: 10px;">Produk yang Dilewati:</h4>';
            skippedProducts.slice(0, 10).forEach(item => {
                skippedHtml += `
                    <div style="background: #fff3cd; padding: 8px; margin-bottom: 8px; border-radius: 6px; font-size: 0.85rem;">
                        <strong>${item.product}</strong> (${item.color}, ${item.size})<br>
                        Data: ${item.data_available}/${item.data_needed} minggu - ${item.reason}
                    </div>
                `;
            });
            if (skippedProducts.length > 10) {
                skippedHtml += `<p style="color: #666; font-size: 0.85rem;">... dan ${skippedProducts.length - 10} produk lainnya</p>`;
            }
            skippedHtml += '</div>';
        }

        resultsContent.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">⚠️</div>
                <h3>Data Tidak Mencukupi</h3>
                <p>${message}</p>
                ${summary ? `
                    <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 8px; text-align: left;">
                        <strong>Ringkasan:</strong><br>
                        • Total Varian Produk: ${summary.total_product_variants || summary.total_products}<br>
                        • Berhasil Diproses: ${summary.processed}<br>
                        • Dilewati: ${summary.skipped}<br>
                        ${summary.suggestion ? `<br><em>${summary.suggestion}</em>` : ''}
                    </div>
                ` : ''}
                ${skippedHtml}
            </div>
        `;
        resetButton();
    }

    function showSkippedInfo(skippedProducts, summary) {
        if (skippedProducts.length === 0) return;

        const infoHtml = `
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-top: 20px; border-radius: 8px;">
                <strong>⚠️ Perhatian:</strong> ${skippedProducts.length} produk dilewati karena data tidak mencukupi.
                <button onclick="showSkippedModal()" class="btn-export" style="margin-left: 10px; padding: 5px 15px; display: inline-block;">
                    Lihat Detail
                </button>
            </div>
            <div id="skippedModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; padding: 50px;">
                <div style="background: white; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 16px; max-height: 80vh; overflow-y: auto;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3>Produk yang Dilewati (${skippedProducts.length})</h3>
                        <button onclick="hideSkippedModal()" style="padding: 5px 15px; background: #dc3545; color: white; border: none; border-radius: 6px; cursor: pointer;">
                            Tutup
                        </button>
                    </div>
                    ${skippedProducts.map(item => `
                        <div style="background: #f8f9fa; padding: 12px; margin-bottom: 10px; border-radius: 8px;">
                            <strong>${item.product}</strong><br>
                            <small>Warna: ${item.color} • Ukuran: ${item.size}</small><br>
                            <small style="color: #dc3545;">${item.reason || 'Data tersedia: ' + item.data_available + '/' + item.data_needed + ' minggu'}</small>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;

        resultsContent.insertAdjacentHTML('beforeend', infoHtml);
    }

    // Global functions for modal
    window.showSkippedModal = function() {
        document.getElementById('skippedModal').style.display = 'block';
    };

    window.hideSkippedModal = function() {
        document.getElementById('skippedModal').style.display = 'none';
    };

    function resetButton() {
        btnPredict.disabled = false;
        btnPredict.classList.remove('processing');
        btnText.innerHTML = 'Mulai Prediksi AI';
        btnPredict.innerHTML = btnText.outerHTML;
    }

    btnExport.addEventListener('click', function() {
        if (forecastData.length === 0) return;

        const csv = convertToCSV(forecastData);
        downloadCSV(csv, 'forecasting-results-' + new Date().toISOString().split('T')[0] + '.csv');
    });

    function convertToCSV(data) {
        // Check if data is from new calculation (has product field) or existing forecast
        if (data[0] && data[0].product) {
            // New calculation data
            const headers = ['Produk', 'Warna', 'Ukuran', 'Prediksi Permintaan', 'Akurasi (%)', 'Tanggal Forecast', 'Minggu Dianalisis', 'Total Demand', 'Rata-rata Demand'];
            const rows = data.map(item => [
                item.product,
                item.product_color,
                item.size,
                item.predicted_demand,
                item.accuracy,
                item.forecast_date,
                item.weeks_analyzed,
                item.total_demand,
                item.average_demand
            ]);
            return [headers, ...rows].map(row => row.join(',')).join('\n');
        } else {
            // Existing forecast data with product info
            const headers = ['Produk', 'Warna', 'Ukuran', 'Prediksi Permintaan', 'Akurasi (%)', 'Tanggal Forecast', 'Minggu Digunakan'];
            const rows = data.map(item => [
                item.product_name || 'N/A',
                item.product_color || 'N/A',
                item.product_size || 'N/A',
                item.predicted_demand,
                item.accurancy,
                item.forecast_date,
                item.week_used
            ]);
            return [headers, ...rows].map(row => row.join(',')).join('\n');
        }
    }

    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }
});
</script>
@endsection

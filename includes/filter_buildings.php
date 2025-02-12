<div class="right-sidebar">
    <div class="title">
        <svg id="close-btn" aria-haspopup="true" aria-expanded="false" onclick="toggleFilter()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25px" height="25px" fill="none"><g fill="currentColor"><path d="M6.25483 6.33003C6.5282 6.05666 6.97141 6.05666 7.24478 6.33003L11.9998 11.0851L16.7548 6.33003C17.0282 6.05666 17.4714 6.05666 17.7448 6.33003C18.0181 6.60339 18.0181 7.04661 17.7448 7.31997L12.9898 12.075L17.7448 16.83C18.0181 17.1034 18.0181 17.5466 17.7448 17.82C17.4714 18.0933 17.0282 18.0933 16.7548 17.82L11.9998 13.0649L7.24478 17.82C6.97141 18.0933 6.5282 18.0933 6.25483 17.82C5.98146 17.5466 5.98146 17.1034 6.25483 16.83L11.0099 12.075L6.25483 7.31997C5.98146 7.04661 5.98146 6.60339 6.25483 6.33003Z"></path></g></svg>
        <h1>Bộ lọc</h1>
    </div>
    <div class="filter-container">
        <label for="price-range">Mức giá (nghìn đồng):</label>
        <form class="filter-form" method="get" action="manage_buildings.php">
        <div class="price-input">
                <div class="field">
                <span>Tối thiểu</span>
                <input type="number" name="min_price" class="input-min" value="<?php echo htmlspecialchars($min_price); ?>">
                </div>
                <div class="separator">-</div>
                <div class="field">
                <span>Tối đa</span>
                <input type="number" name="max_price" class="input-max" value="<?php echo htmlspecialchars($max_price); ?>">
                </div>
            </div>
            <div class="slider-container">
                <div class="slider">
                    <div class="progress"></div>
                </div>
                <div class="range-input">
                    <input type="range" class="range-min" min="0" max="10000" value="<?php echo htmlspecialchars($min_price); ?>" step="1">
                    <input type="range" class="range-max" min="0" max="10000" value="<?php echo htmlspecialchars($max_price); ?>" step="1">
                </div>
            </div>
            <br><br><hr>
            <h3>Tình trạng</h3>
            <select id="building_type" name="status_type">
                <option value="Còn phòng" selected>Còn phòng</option>
                <option value="Hết phòng">Hết phòng</option>
            </select>
            <br><br>
            <button type="submit" id="filter-button">Xem kết quả</button>
        </form>
    </div>
</div>
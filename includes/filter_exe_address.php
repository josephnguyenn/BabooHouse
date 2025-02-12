<?php 
require '../config/database.php';

$data = [
    'Đà Nẵng' => ['Hải Châu', 'Thanh Khê', 'Sơn Trà', 'Ngũ Hành Sơn', 'Liên Chiểu', 'Cẩm Lệ', 'Hòa Vang'],
    'Hồ Chí Minh' => ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7'],
];
$building_types = getDistinctBuildingTypes();
?>
<form class="filter-form" method="get" action="manage_buildings.php">
    <div class="flex-wrap">
        <div class="form-group">
            <label for="city">Thành phố:</label>
            <select id="city" name="city">
                <option value="">Chọn thành phố</option>
                <?php foreach ($data as $city => $districts): ?>
                    <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="district">Quận:</label>
            <select id="district" name="district">
                <option value="">Chọn quận</option>
            </select>
        </div>
        <div class="form-group">
            <label for="building_type">Loại hình cho thuê</label>
            <select id="building_type" name="building_type">
                <option value="" selected>Trống</option>
                <?php foreach ($building_types as $type): ?>
                    <option value="<?php echo htmlspecialchars($type); ?>" ><?php echo htmlspecialchars($type); ?></option>
                <?php endforeach; ?>
            </select>
        </div>    
        <div class="form-group">
            <label for="submit">&#160;</label>
            <button type="submit" style="width: 100px">Lọc</button>
        </div>
    </div>
</form>
<script>
    const districtsData = <?php echo json_encode($data); ?>;
    const selectedDistrict = '';
    const selectedCity = ''; 

    document.addEventListener('DOMContentLoaded', function() {
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');

        citySelect.addEventListener('change', function() {
            districtSelect.innerHTML = '<option value="">Chọn quận</option>';
            const selectedCity = this.value;

            if (selectedCity && districtsData[selectedCity]) {
                districtsData[selectedCity].forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district;
                    option.textContent = district;
                    if (district === selectedDistrict) {
                        option.selected = true; 
                    }
                    districtSelect.appendChild(option);
                });
            }
        });

        if (selectedCity) {
            citySelect.value = selectedCity;
            citySelect.dispatchEvent(new Event('change'));
        }
    });
</script>
<?php
// Expects $project (array, may be empty for a new project) in scope.
$p = $project ?? [];
$types = ['Flat', 'Plot', 'Villa', 'Commercial'];
$statuses = ['Available', 'Sold', 'Coming Soon'];
?>
<div class="form-group">
  <label for="title">Title</label>
  <input type="text" id="title" name="title" required value="<?= htmlspecialchars($p['title'] ?? '') ?>">
</div>

<div class="form-group">
  <label for="type">Type</label>
  <select id="type" name="type" required>
    <?php foreach ($types as $type): ?>
      <option value="<?= $type ?>" <?= ($p['type'] ?? '') === $type ? 'selected' : '' ?>><?= $type ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label for="location">Location</label>
  <input type="text" id="location" name="location" value="<?= htmlspecialchars($p['location'] ?? '') ?>">
</div>

<div class="form-group">
  <label for="price">Price</label>
  <input type="text" id="price" name="price" placeholder="e.g. Rs 32 Lakh onwards" value="<?= htmlspecialchars($p['price'] ?? '') ?>">
</div>

<div class="form-group">
  <label for="size">Size</label>
  <input type="text" id="size" name="size" placeholder="e.g. 1200 sq. ft." value="<?= htmlspecialchars($p['size'] ?? '') ?>">
</div>

<div class="form-group">
  <label for="status">Status</label>
  <select id="status" name="status" required>
    <?php foreach ($statuses as $status): ?>
      <option value="<?= $status ?>" <?= ($p['status'] ?? 'Available') === $status ? 'selected' : '' ?>><?= $status ?></option>
    <?php endforeach; ?>
  </select>
</div>

<div class="form-group">
  <label for="rera_number">RERA Number</label>
  <input type="text" id="rera_number" name="rera_number" value="<?= htmlspecialchars($p['rera_number'] ?? '') ?>">
</div>

<div class="form-group">
  <label for="short_desc">Short Description <span style="font-weight:400;">(card preview, max 300 chars)</span></label>
  <textarea id="short_desc" name="short_desc" rows="2" maxlength="300"><?= htmlspecialchars($p['short_desc'] ?? '') ?></textarea>
</div>

<div class="form-group">
  <label for="full_desc">Full Description</label>
  <textarea id="full_desc" name="full_desc" rows="6"><?= htmlspecialchars($p['full_desc'] ?? '') ?></textarea>
</div>

<div class="form-group">
  <label style="display:flex; align-items:center; gap:8px; font-weight:500;">
    <input type="checkbox" name="featured" value="1" style="width:auto;" <?= !empty($p['featured']) ? 'checked' : '' ?>>
    Feature on homepage
  </label>
</div>

<?php if (!empty($existingImages)): ?>
<div class="form-group">
  <label>Existing Images</label>
  <div style="display:flex; flex-wrap:wrap; gap:14px;">
    <?php foreach ($existingImages as $img): ?>
      <label style="text-align:center; font-weight:400; font-size:0.8rem;">
        <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="" style="width:100px; height:75px; object-fit:cover; border-radius:6px; display:block; margin-bottom:6px;">
        <input type="checkbox" name="remove_images[]" value="<?= $img['id'] ?>" style="width:auto;"> Remove
      </label>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<div class="form-group">
  <label for="images">Add Images <span style="font-weight:400;">(JPG/PNG/WEBP, max 8MB each)</span></label>
  <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple>
</div>

<?php if (!empty($currentBrochurePath)): ?>
<div class="form-group">
  <label>Current Brochure</label>
  <p><a href="<?= htmlspecialchars($currentBrochurePath) ?>" target="_blank" rel="noopener" class="link-underline">View current PDF</a></p>
  <label style="display:flex; align-items:center; gap:8px; font-weight:400;">
    <input type="checkbox" name="remove_brochure" value="1" style="width:auto;"> Remove brochure
  </label>
</div>
<?php endif; ?>

<div class="form-group">
  <label for="brochure"><?= !empty($currentBrochurePath) ? 'Replace Brochure PDF' : 'Brochure PDF' ?> <span style="font-weight:400;">(max 10MB)</span></label>
  <input type="file" id="brochure" name="brochure" accept="application/pdf">
</div>

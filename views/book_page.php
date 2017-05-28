<h2><?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?></h2>

<div class="book_cover">
    <img src="<?php if (isset($book['cover'])) echo htmlspecialchars($book['cover']); ?>" alt="<?php if (isset($book['title'])) echo htmlspecialchars($book['title']); ?>" width="100" />
</div>
<div class="book_info">
    <?php if (isset($book['author'])): ?>
        <p><?php echo htmlspecialchars($book['author']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['year'])): ?>
        <p><?php echo htmlspecialchars($book['year']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['isbn'])): ?>
        <p>ISBN: <?php echo htmlspecialchars($book['isbn']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['category'])): ?>
        <p>Kategoorias: <?php echo htmlspecialchars($book['category']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['rating'])): ?>
        <p>Rating: <?php echo htmlspecialchars($book['rating']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['comment'])): ?>
        <p><?php echo htmlspecialchars($book['comment']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['borrower'])): ?>
        <p><?php echo htmlspecialchars($book['borrower']); ?></p>
    <?php endif; ?>
    <?php if (isset($book['borrow_date'])): ?>
        <p><?php echo htmlspecialchars($book['borrow_date']); ?></p>
    <?php endif; ?>
</div>
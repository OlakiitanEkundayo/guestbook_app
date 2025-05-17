<div class="modal">
    <div class="modal-content">
        <h2>Delete message?</h2>
        <p>Are you sure you want to delete this message?</p>
        <form action="delete.php" method="POST">
            <input type="hidden" name="id" value="1">
            <button type="button" onclick="closeModal()">Cancel</button>
            <button type="submit" class="delete">Delete</button>
        </form>
    </div>
</div>
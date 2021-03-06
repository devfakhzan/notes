<?php
    if (!$_COOKIE['jwt']) {
        header('Location: login.php');
    };

    
    require 'vendor\autoload.php';

    $auth = Auth::isAuthenticated($_COOKIE['jwt']);
    $notes = Note::where('user_id', $auth->sub->user_id)->orderBy('updated_at', 'DESC')->get();
?>
<!DOCTYPE html>
<html lang="en">
<?php
include("includes\header.php");
?>

<body>
    <div class="middle">
        <div class="text-center mb-4">
            <h1 class="h1 font-weight-normal">✏️ Notes ✏️</h1>
            <h5 class="h5 mt-1 mb-2 font-weight-normal">My Notes</h1>
                <a class="btn btn-md btn-danger btn-block my-2" href="/logout.php">
                    Logout
                </a>
        </div>

        <form class="notes-main-container" method="POST" action="note/index.php">
            <div>
                <div class="form-label-group full-width">
                    <textarea name="note" id="note" class="form-control" placeholder="Enter your note here" required autofocus></textarea>

                    <div class="form-label-group mt-3">
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Add Note</button>
                    </div>
                </div>
            </div>

            <?php foreach ($notes as $note) : ?>
                <div class="note-container">
                    <div class="note-header-container">
                        <div class="details-container">
                            <?php 
                                echo date('F d, Y h:m A', strtotime($note['updated_at']));
                            ?>
                        </div>
                        <div class="actions-container">
                            <button type="button" data-action="save" value="<?= $note['id'] ?>" class="action btn btn-sm btn-outline-primary mx-1">Save</button>
                            <button type="button" data-action="delete" value="<?= $note['id'] ?>" class="action btn btn-sm btn-outline-danger mx-1">Delete</button>
                        </div>
                    </div>
                    <textarea data-note-id="<?= $note['id'] ?>" class="sticky-note"><?= $note['note'] ?></textarea>
                </div>
            <?php endforeach; ?>

            <?php if (!count($notes)): ?>
                <p class="text-center mt-5">No notes found.</p>
            <?php endif; ?>
        </form>

    </div>

    <script>
        $(document).ready(function() {

            $("button.action").click(function() {
                const id = $(this).val();
                const note = $(`textarea[data-note-id=${id}]`).val();
                switch (this.dataset.action) {
                    case 'save':

                        axios.patch('note/index.php', {
                            id,
                            note
                        }).then((r) => {
                            const data = r.data;

                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            if (data.success) {
                                alert('Edit was successful!');
                                window.location.reload();
                            }
                        });

                        break;
                    
                    case 'delete':

                        axios.delete('note/index.php', {
                            data: {
                                id,
                                note
                            }
                        }).then((r) => {
                            const data = r.data;

                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            if (data.success) {
                                alert('Delete was successful!');
                                window.location.reload();
                            }
                        });

                        break;
                }
            });

        });
    </script>
</body>

</html>
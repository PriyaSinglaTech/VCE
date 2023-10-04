<?php
session_start();
if (empty($_SESSION['userIdVce']))
    header("location:index.php");
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/style-dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <title>Connect with others - User Dashboard</title>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header__left">
                <img src="img/user.png" class="user__header-user" alt="user">
                <span class="header__user-name">Welcome,
                    <?php
                    $q = "SELECT * FROM user_table WHERE user_id = '" . $_SESSION['userIdVce'] . "'";
                    $r = mysqli_query($con, $q);
                    if (mysqli_num_rows($r) > 0) {
                        $row = mysqli_fetch_array($r);
                        echo $row['user_firstname'] . ' ' . $row['user_lastname'];
                    }
                    ?>
                </span>
            </div>
            <div class="header__right">
                <a href="logout.php" class="header__user-logout"><i class="bi bi-box-arrow-right"></i> logout</a>
            </div>
        </header>
        <section class="sidebar">
            <ul class="sidebar__items">
                <a href="dashboard.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </li>
                </a>
                <a href="user-interest.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-bookmark-check"></i>
                        <span>Interest</span>
                    </li>
                </a>
                <a href="user-achievements.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-trophy"></i>
                        <span>Add Achievements</span>
                    </li>
                </a>
                <a href="explore-courses.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-search"></i>
                        <span>Explore Courses</span>
                    </li>
                </a>
                <a href="connect.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-person-fill-add"></i>
                        <span>Connect with others</span>
                    </li>
                </a>
                <a href="group-discussions.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Group Discussions</span>
                    </li>
                </a>
                <a href="profile.php" class="sidebar__item-link">
                    <li class="sidebar__item">
                        <i class="bi bi-pencil-square"></i>
                        <span>Edit Profile</span>
                    </li>
                </a>
            </ul>
        </section>
        <main class="main__content">
            <?php ?>
            <h3 class="user__name-chat">
                Group: <?php
                        echo $_GET['gf'] . " - " . $_GET['gd']; ?>
            </h3>
            <h4 class="user__chat-window">Chat Window</h4>
            <div class="user__chat-container">
                <div class="user__chat-area">
                    <?php
                    $qM = "SELECT * FROM `group_chat` WHERE group_id = '" . $_GET['gid'] . "'";
                    $rM = mysqli_query($con, $qM);
                    if (mysqli_num_rows($rM) > 0) {
                        while ($rowM = mysqli_fetch_array($rM)) {
                            if ($rowM['message_from'] == $_SESSION['userIdVce']) { ?>
                    <p></p>
                    <p class="user__chat-msg-sent">
                        <?php echo $rowM['message']; ?>
                    </p>
                    <?php } else { ?>
                    <?php
                                $selectU = "SELECT * FROM `user_table` WHERE `user_id` = '" . $rowM['message_from'] . "'";
                                $queryU = mysqli_query($con, $selectU);
                                if (mysqli_num_rows($queryU) > 0) {
                                    while ($rowU = mysqli_fetch_array($queryU)) { ?>
                    <p class="user__chat-msg-recieve">
                        <a class="group__chat-user-link"
                            href="connect-profile.php?profile=<?php echo $rowU['user_id'] ?>&fname=<?php echo $rowU['user_firstname']; ?>&lname=<?php echo $rowU['user_lastname']; ?>">
                            <?php echo $rowU['user_firstname'] . " " . $rowU['user_lastname']; ?>
                        </a>:
                        <?php echo $rowM['message']; ?>
                    </p>
                    <p></p>
                    <?php }
                                }
                                ?>
                    <?php }
                        }
                    } else { ?>
                    <span>Start messaging...</span>
                    <?php }
                    ?>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']) ?>" method="post"
                    class="user__chat-typing">
                    <input class="user_chat-input" type="text" placeholder="Type message..." name="u_msg"
                        autocomplete="off">
                    <input type="submit" class="user__chat-btn" value="Send" name="msg_sent">
                </form>
                <?php
                if (isset($_POST['msg_sent'])) {
                    $user_message = mysqli_real_escape_string($con, $_POST['u_msg']);

                    $sin = "INSERT INTO `group_chat` (`group_id`, `message_from`, `message`) VALUES ('" . $_GET['gid'] . "','" . $_SESSION['userIdVce'] . "','$user_message')";
                    $run = mysqli_query($con, $sin);
                    if ($run) {
                        echo ("<script>location.href = '" . $_SERVER['REQUEST_URI'] . "';</script>");
                    }
                }
                ?>
            </div>
        </main>
    </div>
</body>

</html>
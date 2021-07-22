<?php
include ('connection.php');
if (isset($_GET['action']) and $_GET['action'] == 'delete')
{
    $sql = $_GET['path'] == "employees" ? 'DELETE FROM employees WHERE idEmployee = ?' : 'DELETE FROM projects WHERE idProject = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->close();
    mysqli_close($conn);
    $redirect = $_GET['path'] == "employees" ? './?path=employees' : './?path=projects';
    header("Location: " . $redirect);
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Management System</title>
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        .mdc-data-table {
            align-items: center;
            width: 100%;
            margin: auto;
            display: flex;
        }

        .mdc-button:not(:disabled) {
            color: var(--mdc-theme-secondary);

        }
    </style>
</head>
<header class="mdc-top-app-bar">
  <div class="mdc-top-app-bar__row">
    <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-start">
      <span class="mdc-top-app-bar__title">Projects Management System</span>
    </section>
    <section class="mdc-top-app-bar__section mdc-top-app-bar__section--align-end" role="toolbar">
      <a href="./?path=employees" class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Employees">face</a>
      <a href="./?path=projects" class="material-icons mdc-top-app-bar__action-item mdc-icon-button" aria-label="Projects">source</a>
    </section>
  </div>
</header>
<main class="mdc-top-app-bar--fixed-adjust">


<form  method="POST" style="margin: 50px 0;  <?php if (empty($_GET["path"])) echo " display:none;" ?>">
            <label class="mdc-text-field mdc-text-field--filled">
                <span class="mdc-text-field__ripple"></span>
                <input class="mdc-text-field__input" type="text" aria-labelledby="my-label-id" name="add" >
                <span class="mdc-line-ripple"></span>
            </label>
            <input value="add" class="mdc-button mdc-button--outlined" type="submit">
                <span class="mdc-button__ripple"></span>
            </input>
</form>

<?php
include_once ('connection.php');

if (isset($_GET['action']) and $_GET['action'] == 'edit')
{
    include ('connection.php');
    $id = $_GET['id'];
    $stmt = $conn->prepare($_GET['path'] == "employees" ? 'SELECT fullName FROM employees WHERE idEmployee =' . $id . '' : 'SELECT projectName FROM projects WHERE idProject = ' . $id . '');
    $stmt->bind_param("s", $_POST['name']);
    $stmt->execute();
    $stmt->bind_result($name);

    while ($stmt->fetch())
    {

        echo ("
            <form  method=\"POST\" style=\"margin: 50px 0;\">
            <label class=\"mdc-text-field mdc-text-field--filled\">
                <span class=\"mdc-text-field__ripple\"></span>
                <input class=\"mdc-text-field__input\" type=\"text\" aria-labelledby=\"my-label-id\" name=\"update\" value=\"" . $name . "\">
                <span class=\"mdc-line-ripple\"></span>
                    </label>");
        if ($_GET["path"] == "employees")
        {
            print (" <label class=\"mdc-text-field mdc-text-field--filled\">
            <select name=\"\" id=\"\" class=\"mdc-text-field__input\">
                <option value=\"null\" selected>n/a</option>
                <option value=\"test\">nebesugalvoju</option>
            </select>
        
        </label>");
        };
        echo ("
            <input value=\"update\" class=\"mdc-button mdc-button--outlined\" type=\"submit\">
                <span class=\"mdc-button__ripple\"></span>
            
            </input>
        </form>");
    }

    $stmt->close();
    mysqli_close($conn);

}
?>
    

                
<div class="mdc-data-table" <?php if (empty($_GET["path"])) echo "style=\"display:none;\"" ?>>
            <div class="mdc-data-table__table-container mdc-layout-grid">
                <table class="mdc-data-table__table" aria-label="Files browser">
                    <thead>
                        <tr class="mdc-data-table__header-row">
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">ID</th>
                            <th class="mdc-data-table__header-cell mdc-data-table__header-cell--numeric" role="columnheader" scope="col">Name</th>
                            <th class="mdc-data-table__header-cell mdc-data-table__header-cell--numeric" role="columnheader" scope="col"><?php echo $_GET["path"] == "employees" ? "Project" : "Team" ?></th>
                            <th class="mdc-data-table__header-cell mdc-data-table__header-cell--numeric" role="columnheader" scope="col">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="mdc-data-table__content" >
     

          
            <?php
include ('connection.php');
if (count($_POST) > 0)
{
    if (!empty($_POST['update']))
    {
        $sql = $_GET["path"] == "employees" ? "UPDATE employees set fullName='" . $_POST['update'] . "' WHERE idEmployee= ?" : "UPDATE projects set projectName='" . $_POST['update'] . "' WHERE idProject= ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        $redirect = $_GET['path'] == "employees" ? './?path=employees' : './?path=projects';
        header("Location: " . $redirect);
    }
    if (!empty($_POST['add']))
    {
        $sql = $_GET["path"] == "employees" ? "INSERT INTO employees (fullName) VALUES (?);" : "INSERT INTO projects (projectName) VALUES (?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $_POST['add']);
        $stmt->execute();
        $stmt->close();
        mysqli_close($conn);
        $redirect = $_GET['path'] == "employees" ? './?path=employees' : './?path=projects';
        header("Location: " . $redirect);
    }

}
if ($_GET["path"] == "employees" || $_GET["path"] == "projects")
{
    $stmt = $conn->prepare($_GET["path"] == "employees" ? "SELECT employees.idEmployee, employees.fullName, projects.projectName FROM employees LEFT JOIN projects ON employees.idProject= projects.idProject;" : "SELECT projects.idProject, projects.projectName, group_concat(employees.fullName) FROM projects LEFT JOIN employees ON projects.idProject= employees.idProject GROUP BY idProject;"); // 1. formuojamas užklausos šablonas
    $stmt->bind_param("i", $_POST['idEmployee']); // 2. Ima šabloną ir įdeda vietoje klaustuko tą reikšmę: $_POST['id'], aišku prieš tai padaro tam tikrą patikrinimą / “dezinfekciją”. Pirmas parametras yra duomenų tipas: i - skaičius (integer), s - string, d - double.
    $stmt->execute(); // 3. Suformuota užklausa siunčiama į mysql
    $stmt->bind_result($id, $fn, $pr); // 4. pozicinis bindinimas (ne pagal pavadinimą stulpelio) id → $id, o firstname → $fn
    while ($stmt->fetch())
    {

        echo ("
        <tr class=\"mdc-data-table__row\">
            <th class=\"mdc-data-table__cell\" scope=\"row\">" . $id . "</th>
            <td class=\"mdc-data-table__cell mdc-data-table__cell--numeric\">" . $fn . "</td>
            <td class=\"mdc-data-table__cell mdc-data-table__cell--numeric\">" . $pr . "</td>
            <td class=\"mdc-data-table__cell mdc-data-table__cell--numeric\"><a href=\"" . $_SERVER['REQUEST_URI'] . "&action=edit&id=" . $id . "\" class=\"mdc-button\" >Edit</a><a href=\"" . $_SERVER['REQUEST_URI'] . "&action=delete&id=" . $id . "\" class=\"mdc-button\">Delete</a></td>

        </tr>");

    }

    $stmt->close();
}
mysqli_close($conn);
?>
         
                </tbody>
                </table>
                </div>
</main>

</html>

# Usage with docker

In this page I'll show how to use our Docker image in your PHP project.

Create a directory to your simple project and add this index.php file.

```php
<?php
if (!isset($_GET['n'])) {
    die("You must pass an json array in variable ?n. Example: ?n=[5,4,3,2,1]");
}

function quickSort($arr)
{
    if(count($arr) <= 1){
        return $arr;
    }

    $pivot = $arr[0];
    $left = array();
    $right = array();

    for($i = 1, $length = count($arr); $i < $length; $i++)
    {
        if($arr[$i] < $pivot){
            $left[] = $arr[$i];
            continue;
        }
        $right[] = $arr[$i];
    }

    xdebug_break(); // Breakpoint
    return array_merge(quickSort($left), array($pivot), quickSort($right));
}

$listOfNumbers = json_decode($_GET['n']);
echo json_encode(quickSort($listOfNumbers));
```

Now you have your project.

### Usage with Docker in CLI

Open your folder

```bash
$ cd /path/to/my/project
```

And start your docker server:

```bash
$ docker run --rm -it -v $PWD:/app -w /app -p 8888:8888 --name debugProject -d tacnoman/dephpugger:latest
```

Open in your browser `http://localhost:8888/?n=[62,42,75,56,83,12]`.

You'll se the numbers sorted.

Now, run:

```bash
$ docker exec -it debugProject dephpugger debug
```

Active your browser debug and refresh the page.

![dephpugger](https://raw.githubusercontent.com/tacnoman/dephpugger/master/images/docker-demo.png)

Now, you can configure your docker-compose.yml and use it :D

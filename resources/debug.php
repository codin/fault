<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internal Server Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        <?php echo file_get_contents(__DIR__ . '/styles.css'); ?>
    </style>
</head>
<body>
    <div class="container">
        <?php foreach ($stack as $trace): ?>
        <div class="section">
            <h2><?php echo e($trace->getExceptionClassName()); ?></h2>
            <p><?php echo e($trace->getException()->getMessage()); ?></p>
            <p class="quiet">
                <code><?php echo $trace->getException()->getFile(); ?></code>
                on line
                <code><?php echo $trace->getException()->getLine(); ?></code>
            </p>
            <div class="block">
            <?php
            $lines = $trace->getContext()->getPlaceInFile();
            $pad = \strlen((string) \max(\array_keys($lines)));
            foreach ($lines as $lineNumber => $code) {
                $line = \str_pad((string) $lineNumber, $pad, ' ', STR_PAD_LEFT);
                $class = ['line'];
                if ($trace->getException()->getLine() == $lineNumber) {
                    $class[] = 'highlight';
                }
                $className = \implode(' ', $class);
                printf(
                    '<span class="%s"><span class="line-number">%s</span> %s</span>',
                    $className,
                    $line,
                    e($code)
                );
            }
            ?>
            </div>

            <p><b>Frames</b></p>
            <?php foreach ($trace->getFrames() as $frame): ?>
            <div class="frame">
                <div class="frame-header">
                    <span class="quiet">
                        <code><?php echo $frame->getCaller(); ?></code>
                        <?php if ($frame->getFile()): ?>
                        in <code><?php echo $frame->getFile(); ?></code> on line <code><?php echo $frame->getLine(); ?></code>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($frame->getFile()): ?>
                <div class="frame-body">

                    <div class="block">
                    <?php
                    $lines = $frame->getContext()->getPlaceInFile();
                    $pad = \strlen((string) \max(\array_keys($lines)));
                    foreach ($lines as $lineNumber => $code) {
                        $line = \str_pad((string) $lineNumber, $pad, ' ', STR_PAD_LEFT);
                        $class = ['line'];
                        if ($e->getLine() == $lineNumber) {
                            $class[] = 'highlight';
                        }
                        $className = \implode(' ', $class);
                        printf(
                            '<span class="%s"><span class="line-number">%s</span> %s</span>',
                            $className,
                            $line,
                            e($code)
                        );
                    }
                    ?>
                    </div>

                    <?php if ($frame->getArguments()): ?>
                    <p><b>Arguments</b></p>
                    <table class="table">
                        <tbody>
                            <?php foreach ($frame->getArguments() as $name => $arg): ?>
                            <tr>
                                <td width="20%"><code><?php echo $name; ?></code></td>
                                <td><code><?php echo e($arg); ?></code></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <script>
        (function() {
            const toggle = function(event) {
                let header = event.target;
                while(!header.classList.contains('frame-header')) {
                    header = header.parentNode;
                }
                let frame = header.parentNode,
                    body = frame.querySelector('.frame-body');
                if(body) {
                    event.preventDefault();
                    header.classList.toggle('frame-header--open');
                    body.classList.toggle('frame-body--open');
                }
            };
            const elements = document.querySelectorAll('.frame-header');
            Array.prototype.forEach.call(elements, function(element) {
                element.addEventListener('click', toggle);
            });
        })();
    </script>
</body>
</html>

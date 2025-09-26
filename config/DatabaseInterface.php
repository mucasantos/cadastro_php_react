<?php
/**
 * Interface para abstração do banco de dados
 * Seguindo o Dependency Inversion Principle (DIP)
 */
interface DatabaseInterface {
    public function getConnection();
    public function beginTransaction();
    public function commit();
    public function rollback();
}
?>
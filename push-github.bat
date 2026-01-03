@echo off
echo ================================================
echo   Git Setup untuk E-Commerce Project
echo ================================================
echo.
echo Pastikan Git sudah terinstall!
echo Download: https://git-scm.com/download/win
echo.
pause

cd /d C:\laragon\www\e-comers

echo.
echo [1/7] Initializing Git...
git init

echo.
echo [2/7] Adding all files...
git add .

echo.
echo [3/7] Creating first commit...
git commit -m "Initial commit: E-commerce Toko Baju - PHP Native"

echo.
echo [4/7] Renaming branch to main...
git branch -M main

echo.
echo ================================================
echo PENTING: Salin URL repository GitHub Anda!
echo Contoh: https://github.com/username/repo-name.git
echo ================================================
echo.
set /p GITHUB_URL="Paste URL GitHub repository Anda: "

echo.
echo [5/7] Adding remote origin...
git remote add origin %GITHUB_URL%

echo.
echo [6/7] Pushing to GitHub...
git push -u origin main

echo.
echo [7/7] SELESAI!
echo.
echo ================================================
echo   Project berhasil di-push ke GitHub!
echo ================================================
echo.
echo Sekarang teman Anda bisa clone dengan:
echo   git clone %GITHUB_URL%
echo.
pause

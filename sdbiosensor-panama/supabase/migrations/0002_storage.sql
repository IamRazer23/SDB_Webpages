-- ============================================================
-- SD Biosensor Panamá — Supabase Storage (imágenes, catálogos, descargas)
-- Reemplaza el disco local de Laravel (image_path / cover_image_path / file_path).
-- ============================================================

-- Buckets:
--   media     → imágenes de productos/noticias (público, solo lectura).
--   documents → catálogos PDF, IFU, manuales (público, solo lectura).
insert into storage.buckets (id, name, public)
values ('media', 'media', true), ('documents', 'documents', true)
on conflict (id) do nothing;

-- Lectura pública de ambos buckets ------------------------------------------
create policy "public read media"
    on storage.objects for select
    to anon, authenticated
    using (bucket_id in ('media', 'documents'));

-- Escritura/borrado SOLO para service_role (Laravel/admin), nunca anon -------
create policy "service write media"
    on storage.objects for insert
    to service_role
    with check (bucket_id in ('media', 'documents'));

create policy "service update media"
    on storage.objects for update
    to service_role
    using (bucket_id in ('media', 'documents'));

create policy "service delete media"
    on storage.objects for delete
    to service_role
    using (bucket_id in ('media', 'documents'));
